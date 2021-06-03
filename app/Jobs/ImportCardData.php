<?php

namespace App\Jobs;

use App\Actions\DownloadFileAction;
use App\Models\Card;
use App\Models\CardGeneric;
use App\Models\Color;
use App\Models\FrameEffect;
use App\Models\Keyword;
use App\Models\Set;
use App\Models\Subtype;
use App\Models\Supertype;
use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use pcrov\JsonReader\Exception;
use pcrov\JsonReader\InputStream\IOException;
use pcrov\JsonReader\InvalidArgumentException;
use pcrov\JsonReader\JsonReader;
use phpDocumentor\Reflection\Type;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class ImportCardData implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        // Pricing data URL
        $file = [
            'url'           => 'https://mtgjson.com/api/v5/AllPrintings.json',
            'format'        => 'json',
            'storage_path'  => 'dumps/printings',
        ];

        $save_file_loc = (new DownloadFileAction())->execute($file, 'Ymd', 5);

        // open json file and get data
        $reader = new JsonReader();

        try {
            $reader->open($save_file_loc);
        } catch (IOException | InvalidArgumentException $e) {
        }
        $reader->read();
        $reader->read('data');

        $reader->read();

        // loop through sets
        while ($reader->type() === JsonReader::OBJECT) {
            $data = $reader->value();

            $set = $this->saveSet($data);
            echo 'set: ' . $set->name . PHP_EOL;

            foreach ($data['cards'] as $cardData) {
                $card = $this->saveCard($cardData);
                $set->cards()->attach($card->id);
                $this->attachColors($cardData, $card);
                $this->attachForeignData($cardData, $card);
                $this->attachFrameEffects($cardData, $card);
                $this->attachKeywords($cardData, $card);
                $this->attachLegalities($cardData, $card);
                $this->attachPrintings($cardData, $card);
                $this->attachRulings($cardData, $card);
                $this->attachSubtypes($cardData, $card);
                $this->attachSupertypes($cardData, $card);
                $this->attachTypes($cardData, $card);
                $this->attachVariations($cardData, $card);
                /*
                 * availability
                 * colorIdentity
                 * leadershipSkills
                 * imagePath
                 * price_foil
                 * price_normal
                 */
            }

            if ($tokens = $this->ifKey($data, 'tokens')) {
                foreach ($tokens as $tokenData) {
                    $token = $this->saveToken($tokenData);
                    $set->tokens()->attach($token->id);
                    $this->attachColors($tokenData, $token);
                    $this->attachKeywords($tokenData, $token);
                    $this->attachSubtypes($tokenData, $token);
                    $this->attachSupertypes($tokenData, $token);
                    $this->attachTypes($tokenData, $token);
                    $this->attachCards($tokenData, $token, $set);
                }
            }

            $reader->next();
        }

        $reader->close();

        echo 'Completed' . PHP_EOL;
    }

    /**
     * Attach cards to tokens
     *
     * @param array $data
     * @param Token $token
     * @param Set $set
     */
    private function attachCards(array $data, Token $token, Set $set) : void
    {
        if ($related = $this->ifKey($data, 'reverseRelated')) {
            foreach ($related as $item) {
                $card = $set->cards()->firstOrCreate([
                    'name' => $item,
                ]);
                $token->cards()->attach($card->id);
            }
        }
    }

    /**
     * Attach an array of colors to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachColors(array $data, CardGeneric $card) : void
    {
        if ($colors = $this->ifKey($data, 'colors')) {
            foreach ($colors as $colorName) {
                $color = Color::firstOrCreate([
                    'name' => $colorName,
                ]);
                $card->colors()->attach($color->id);
            }
        }
    }

    /**
     * Attach an array of foreign data to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachForeignData(array $data, CardGeneric $card) : void
    {
        if ($foreignDatas = $this->ifKey($data, 'foreignData')) {
            foreach ($foreignDatas as $foreignData) {
                $card->foreignData()->updateOrCreate(
                    [
                        'language'     => $foreignData['language'],
                        'multiverseid' => $foreignData['multiverseId'],
                    ],
                    [
                        'flavorText'  => $this->ifKey($foreignData, 'flavorText'),
                        'name'        => $this->ifKey($foreignData, 'name'),
                        'text'        => $this->ifKey($foreignData, 'text'),
                        'type'        => $this->ifKey($foreignData, 'type'),
                    ]
                );
            }
        }
    }

    /**
     * Attach an array of frame effects to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachFrameEffects(array $data, CardGeneric $card) : void
    {
        if ($frameEffects = $this->ifKey($data, 'frameEffects')) {
            foreach ($frameEffects as $effect) {
                $frameEffect = FrameEffect::firstOrCreate(
                    [
                        'name' => $effect,
                    ]
                );
                $card->frameEffects()->attach($frameEffect);
            }
        }
    }

    /**
     * Attach an array of keywords to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachKeywords(array $data, CardGeneric $card) : void
    {
        if ($keywords = $this->ifKey($data, 'keywords')) {
            foreach ($keywords as $keywordName) {
                $keyword = Keyword::firstOrCreate([
                    'name' => $keywordName,
                ]);
                $card->keywords()->attach($keyword->id);
            }
        }
    }

    /**
     * Attach an array of legalities to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachLegalities(array $data, CardGeneric $card) : void
    {
        if ($legalities = $this->ifKey($data, 'legalities')) {
            foreach ($legalities as $legalityName => $legalityValue) {
                $card->legalities()->updateOrCreate(
                    ['format' => $legalityName],
                    ['status' => $legalityValue]
                );
            }
        }
    }

    /**
     * Attach an array of printings to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachPrintings(array $data, CardGeneric $card) : void
    {
        if ($printings = $this->ifKey($data, 'printings')) {
            foreach ($printings as $printing) {
                if ($printing == $card->set->code) {
                    continue;
                }
                $set         = Set::firstOrCreate(['code' => $printing]);
                $setPrinting = $set->cards()->firstOrCreate([
                    'name' => $card->name,
                ]);
                $card->printings()->attach($setPrinting->id);
            }
        }
    }

    /**
     * Attach an array of rulings to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachRulings(array $data, CardGeneric $card) : void
    {
        if ($rulings = $this->ifKey($data, 'rulings')) {
            foreach ($rulings as $ruling) {
                $card->rulings()->firstOrCreate([
                    'date' => $ruling['date'],
                    'text' => $ruling['text'],
                ]);
            }
        }
    }

    /**
     * Attach an array of subtypes to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachSubtypes(array $data, CardGeneric $card) : void
    {
        if ($subTypes = $this->ifKey($data, 'subtypes')) {
            foreach ($subTypes as $subTypeName) {
                $subType = Subtype::firstOrCreate([
                    'name' => $subTypeName,
                ]);
                $card->subtypes()->attach($subType->id);
            }
        }
    }

    /**
     * Attach an array of supertypes to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachSupertypes(array $data, CardGeneric $card) : void
    {
        if ($superTypes = $this->ifKey($data, 'supertypes')) {
            foreach ($superTypes as $superTypeName) {
                $superType = Supertype::firstOrCreate([
                    'name' => $superTypeName,
                ]);
                $card->supertypes()->attach($superType->id);
            }
        }
    }

    /**
     * Attach an array of types to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachTypes(array $data, CardGeneric $card) : void
    {
        if ($types = $this->ifKey($data, 'types')) {
            foreach ($types as $typeName) {
                $type = Type::firstOrCreate([
                    'name' => $typeName,
                ]);
                $card->types()->attach($type->id);
            }
        }
    }

    /**
     * Attach an array of card variations to a card
     *
     * @param array $data
     * @param Card $card
     */
    private function attachVariations(array $data, CardGeneric $card) : void
    {
        if ($variations = $this->ifKey($data, 'variations')) {
            foreach ($variations as $variation) {
                $variationCard = Card::firstOrCreate(
                    [
                        'uuid' => $variation,
                        'name' => $card->name,
                    ]
                );
                $card->variations()->attach($variationCard->id);
            }
        }
    }

    /**
     * if key exists in array, return its value, otherwise return null
     *
     * @param array $haystack
     * @param string $needle
     * @return mixed|null
     */
    private function ifKey(array $haystack, string $needle)
    {
        if (!array_key_exists($needle, $haystack)) {
            return null;
        }

        return $haystack[$needle];
    }

    /**
     * if key exists in array, recursively, return its value, otherwise return null
     *
     * @param array $haystack
     * @param string $needle
     * @return mixed|null
     */
    private function recursiveFind(array $haystack, string $needle)
    {
        $iterator  = new RecursiveArrayIterator($haystack);
        $recursive = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($recursive as $key => $value) {
            if ($key === $needle) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Save a card
     *
     * @param array $data
     * @return Card
     */
    private function saveCard(array $data) : Card
    {
        return Card::updateOrCreate(
            [
                'uuid' => $data['uuid'],
                'name' => $data['name'],
            ],
            [
                'artist'                   => $this->ifKey($data, 'artist'),
                'asciiName'                => $this->ifKey($data, 'asciiName'),
                'borderColor'              => $this->ifKey($data, 'borderColor'),
                'cardKingdomFoilId'        => $this->recursiveFind($data, 'cardKingdomFoilId'),
                'cardKingdomId'            => $this->recursiveFind($data, 'cardKingdomId'),
                'convertedManaCost'        => $this->ifKey($data, 'convertedManaCost'),
                'duelDeck'                 => $this->ifKey($data, 'duelDeck'),
                'edhrecRank'               => $this->ifKey($data, 'edhrecRank'),
                'faceConvertedManaCost'    => $this->ifKey($data, 'faceConvertedManaCost'),
                'faceName'                 => $this->ifKey($data, 'faceName'),
                'flavorName'               => $this->ifKey($data, 'flavorName'),
                'flavorText'               => $this->ifKey($data, 'flavorText'),
                'frameVersion'             => $this->ifKey($data, 'frameVersion'),
                'hand'                     => $this->ifKey($data, 'hand'),
                'hasAlternativeDeckLimit'  => $this->ifKey($data, 'hasAlternativeDeckLimit'),
                'hasFoil'                  => $this->ifKey($data, 'hasFoil'),
                'hasNonFoil'               => $this->ifKey($data, 'hasNonFoil'),
                'isAlternative'            => $this->ifKey($data, 'isAlternative'),
                'isFullArt'                => $this->ifKey($data, 'isFullArt'),
                'isOnlineOnly'             => $this->ifKey($data, 'isOnlineOnly'),
                'isOversized'              => $this->ifKey($data, 'isOversized'),
                'isPromo'                  => $this->ifKey($data, 'isPromo'),
                'isReprint'                => $this->ifKey($data, 'isReprint'),
                'isReserved'               => $this->ifKey($data, 'isReserved'),
                'isStarter'                => $this->ifKey($data, 'isStarter'),
                'isStorySpotlight'         => $this->ifKey($data, 'isStorySpotlight'),
                'isTextless'               => $this->ifKey($data, 'isTextless'),
                'isTimeshifted'            => $this->ifKey($data, 'isTimeshifted'),
                'layout'                   => $this->ifKey($data, 'layout'),
                'leadershipSkills'         => $this->ifKey($data, 'leadershipSkills'),
                'life'                     => $this->ifKey($data, 'life'),
                'loyalty'                  => $this->ifKey($data, 'loyalty'),
                'manaCost'                 => $this->ifKey($data, 'manaCost'),
                'mcmId'                    => $this->recursiveFind($data, 'mcmId'),
                'mcmMetaId'                => $this->recursiveFind($data, 'mcmMetaId'),
                'mtgArenaId'               => $this->recursiveFind($data, 'mtgArenaId'),
                'mtgjsonV4Id'              => $this->recursiveFind($data, 'mtgjsonV4Id'),
                'mtgoFoilId'               => $this->recursiveFind($data, 'mtgoFoilId'),
                'mtgoId'                   => $this->recursiveFind($data, 'mtgoId'),
                'multiverseId'             => $this->recursiveFind($data, 'multiverseId'),
                'number'                   => $this->ifKey($data, 'number'),
                'originalReleaseDate'      => $this->ifKey($data, 'originalReleaseDate'),
                'originalText'             => $this->ifKey($data, 'originalText'),
                'originalType'             => $this->ifKey($data, 'originalType'),
                'otherFaceIds'             => $this->ifKey($data, 'otherFaceIds'),
                'power'                    => $this->ifKey($data, 'power'),
                'rarity'                   => $this->ifKey($data, 'rarity'),
                'scryfallId'               => $this->recursiveFind($data, 'scryfallId'),
                'scryfallIllustrationId'   => $this->recursiveFind($data, 'scryfallIllustrationId'),
                'scryfallOracleId'         => $this->recursiveFind($data, 'scryfallOracleId'),
                'side'                     => $this->ifKey($data, 'side'),
                'tcgplayerProductId'       => $this->recursiveFind($data, 'tcgplayerProductId'),
                'text'                     => $this->ifKey($data, 'text'),
                'toughness'                => $this->ifKey($data, 'toughness'),
                'watermark'                => $this->ifKey($data, 'watermark'),
            ]
        );
    }

    /**
     * Save a set
     *
     * @param array $data
     * @return Set
     */
    private function saveSet(array $data) : Set
    {
        return Set::updateOrCreate(
            ['code' => $data['code']],
            [
                'baseSetSize'       => $this->ifKey($data, 'baseSetSize'),
                'block'             => $this->ifKey($data, 'block'),
                'isFoilOnly'        => $this->ifKey($data, 'isFoilOnly'),
                'isForeignOnly'     => $this->ifKey($data, 'isForeignOnly'),
                'isNonFoilOnly'     => $this->ifKey($data, 'isNonFoilOnly'),
                'isOnlineOnly'      => $this->ifKey($data, 'isOnlineOnly'),
                'isPartialPreview'  => $this->ifKey($data, 'isPartialPreview'),
                'keyruneCode'       => $this->ifKey($data, 'keyruneCode'),
                'mcmid'             => $this->ifKey($data, 'mcmid'),
                'mcmIdExtras'       => $this->ifKey($data, 'mcmIdExtras'),
                'mcmName'           => $this->ifKey($data, 'mcmName'),
                'mtgoCode'          => $this->ifKey($data, 'mtgoCode'),
                'name'              => $this->ifKey($data, 'name'),
                'parentCode'        => $this->ifKey($data, 'parentCode'),
                'releaseDate'       => $this->ifKey($data, 'releaseDate'),
                'tcgplayerGroupId'  => $this->ifKey($data, 'tcgplayerGroupId'),
                'totalSetSize'      => $this->ifKey($data, 'totalSetSize'),
                'type'              => $this->ifKey($data, 'type'),
            ]
        );
    }

    /**
     * Save a Token
     *
     * @param array $data
     * @return Token
     */
    private function saveToken(array $data) : Token
    {
        return Token::updateOrCreate(
            [
                'uuid' => $data['uuid'],
                'name' => $data['name'],
            ],
            [
                'artist'                   => $this->ifKey($data, 'artist'),
                'asciiName'                => $this->ifKey($data, 'asciiName'),
                'borderColor'              => $this->ifKey($data, 'borderColor'),
                'edhrecRank'               => $this->ifKey($data, 'edhrecRank'),
                'faceName'                 => $this->ifKey($data, 'faceName'),
                'flavorText'               => $this->ifKey($data, 'flavorText'),
                'frameVersion'             => $this->ifKey($data, 'frameVersion'),
                'hasFoil'                  => $this->ifKey($data, 'hasFoil'),
                'hasNonFoil'               => $this->ifKey($data, 'hasNonFoil'),
                'isFullArt'                => $this->ifKey($data, 'isFullArt'),
                'isPromo'                  => $this->ifKey($data, 'isPromo'),
                'isReprint'                => $this->ifKey($data, 'isReprint'),
                'layout'                   => $this->ifKey($data, 'layout'),
                'mcmId'                    => $this->recursiveFind($data, 'mcmId'),
                'mtgArenaId'               => $this->recursiveFind($data, 'mtgArenaId'),
                'mtgjsonV4Id'              => $this->recursiveFind($data, 'mtgjsonV4Id'),
                'multiverseId'             => $this->recursiveFind($data, 'multiverseId'),
                'number'                   => $this->ifKey($data, 'number'),
                'originalText'             => $this->ifKey($data, 'originalText'),
                'originalType'             => $this->ifKey($data, 'originalType'),
                'power'                    => $this->ifKey($data, 'power'),
                'scryfallId'               => $this->recursiveFind($data, 'scryfallId'),
                'scryfallIllustrationId'   => $this->recursiveFind($data, 'scryfallIllustrationId'),
                'scryfallOracleId'         => $this->recursiveFind($data, 'scryfallOracleId'),
                'side'                     => $this->ifKey($data, 'side'),
                'tcgplayerProductId'       => $this->recursiveFind($data, 'tcgplayerProductId'),
                'text'                     => $this->ifKey($data, 'text'),
                'toughness'                => $this->ifKey($data, 'toughness'),
                'type'                     => $this->ifKey($data, 'type'),
                'watermark'                => $this->ifKey($data, 'watermark'),
            ]
        );
    }
}
