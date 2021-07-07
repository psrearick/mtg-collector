<?php

namespace App\Jobs;

use App\Actions\DownloadFileAction;
use App\Domain\CardAttributes\Models\Color;
use App\Domain\CardAttributes\Models\FrameEffect;
use App\Domain\CardAttributes\Models\Game;
use App\Domain\CardAttributes\Models\Keyword;
use App\Domain\CardAttributes\Models\PromoType;
use App\Domain\CardAttributes\Models\RelatedObjects;
use App\Domain\Cards\Models\Card;
use App\Domain\Prices\Models\PriceProvider;
use App\Domain\Sets\Models\Set;
use App\Domain\Symbols\Models\Symbol;
use Http;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use pcrov\JsonReader\Exception;
use pcrov\JsonReader\InputStream\IOException;
use pcrov\JsonReader\InvalidArgumentException;
use pcrov\JsonReader\JsonReader;

class ImportScryfallData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * Download bulk data and return file name
     *
     * @return string
     */
    public function getFile() : string
    {
        $bulkDataDefinition = Http::get('https://api.scryfall.com/bulk-data/default-cards')->json();
        $downloadUri        = $bulkDataDefinition['download_uri'];

        $file = [
            'url'           => $downloadUri,
            'format'        => 'json',
            'storage_path'  => 'dumps/scryfall',
            'name'          => 'default_cards',
        ];

        return (new DownloadFileAction())->execute($file, 'Ymd', 5);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        echo 'Updating Sets' . PHP_EOL;
        $this->updateSets();

        echo 'Fetching Card Data' . PHP_EOL;
        $save_file_loc = $this->getFile();
        echo 'Processing Card Data' . PHP_EOL;

        try {
            $this->processCardData($save_file_loc);
        } catch (InvalidArgumentException | Exception $e) {
        }

        echo 'Saving Symbols' . PHP_EOL;
        $this->updateSymbols();

        echo 'Completed' . PHP_EOL;
    }

    /**
     * @param string $fileName
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function processCardData(string $fileName) : void
    {
        $reader = new JsonReader();

        try {
            $reader->open($fileName);
        } catch (IOException | \InvalidArgumentException $e) {
        }

        $reader->read();
        $reader->read();

        while ($reader->type() === JsonReader::OBJECT) {
            $cardData = $reader->value();

            if ($cardData['object'] == 'card') {
                $card = $this->updateCard($cardData);
                $this->updatePricing($cardData, $card);
            }

            $reader->next();
        }

        $reader->close();
    }

    /**
     * @param Card $card
     */
    public function saveImage(Card $card) : void
    {
        ImportCardImages::dispatch($card, $card->imageNormalUri);
    }

    /**
     * Set the card's set
     *
     * @param array $cardData
     * @param Card $card
     */
    public function setCardSet(array $cardData, Card $card) : void
    {
        if (!array_key_exists('set', $cardData)) {
            return;
        }
        $set = Set::firstOrCreate(
            [
                'setId' => $cardData['set_id'],
            ],
            [
                'code'  => $cardData['set'],
                'name'  => $cardData['set_name'],
                'type'  => $cardData['set_type'],
            ]
        );
        $card->set()->associate($set);
        $card->save();
    }

    /**
     * Add the relationships for each of the cards color fields
     *
     * @param array $cardData
     * @param Card $card
     */
    public function setColorFields(array $cardData, Card $card) : void
    {
        $types = ['color_identity', 'color_indicator', 'colors', 'produced_mana'];
        foreach ($types as $type) {
            if (array_key_exists($type, $cardData)) {
                foreach ($cardData[$type] as $colorValue) {
                    $color = Color::firstOrCreate(
                        [
                            'name' => $colorValue,
                        ]
                    );
                    $card->colors()->syncWithoutDetaching($color->id, ['type' => $type]);
                }
            }
        }
    }

    /**
     * Add the faces relationships for cards
     *
     * @param array $cardData
     * @param Card $card
     */
    public function setFaces(array $cardData, Card $card) : void
    {
        if (array_key_exists('card_faces', $cardData)) {
            foreach ($cardData['card_faces'] as $card_face) {
                $card->faces()->create([
                    'name'               => $this->ifKey($card_face, 'name'),
                    'artist'             => $this->ifKey($card_face, 'artist'),
                    'flavorText'         => $this->ifKey($card_face, 'flavor_text'),
                    'illustrationId'     => $this->ifKey($card_face, 'illustration_id'),
                    'loyalty'            => $this->ifKey($card_face, 'loyalty'),
                    'manaCost'           => $this->ifKey($card_face, 'mana_cost'),
                    'oracleText'         => $this->ifKey($card_face, 'oracle_text'),
                    'power'              => $this->ifKey($card_face, 'power'),
                    'printedName'        => $this->ifKey($card_face, 'printed_name'),
                    'printedText'        => $this->ifKey($card_face, 'printed_text'),
                    'printedTypeLine'    => $this->ifKey($card_face, 'printed_type_line'),
                    'toughness'          => $this->ifKey($card_face, 'toughness'),
                    'typeLine'           => $this->ifKey($card_face, 'type_line'),
                    'watermark'          => $this->ifKey($card_face, 'watermark'),
                ]);
            }
        }
    }

    /**
     * Add the relationship for the cards frame effects
     *
     * @param array $cardData
     * @param Card $card
     */
    public function setFrameEffects(array $cardData, Card $card) : void
    {
        if (array_key_exists('frame_effects', $cardData)) {
            foreach ($cardData['frame_effects'] as $frame_effect) {
                $frameEffect = FrameEffect::firstOrCreate(
                    [
                        'name' => $frame_effect,
                    ]
                );
                $card->frameEffects()->syncWithoutDetaching($frameEffect->id);
            }
        }
    }

    /**
     * @param array $cardData
     * @param Card $card
     */
    public function setGames(array $cardData, Card $card) : void
    {
        if (array_key_exists('games', $cardData)) {
            foreach ($cardData['games'] as $gameName) {
                $game = Game::firstOrCreate(
                    [
                        'name' => $gameName,
                    ]
                );
                $card->games()->syncWithoutDetaching($game->id);
            }
        }
    }

    /**
     * Add the relationships for the cards keywords
     *
     * @param array $cardData
     * @param Card $card
     */
    public function setKeywords(array $cardData, Card $card) : void
    {
        if (array_key_exists('keywords', $cardData)) {
            foreach ($cardData['keywords'] as $keywordName) {
                $keyword = Keyword::firstOrCreate(
                    [
                        'name' => $keywordName,
                    ]
                );
                $card->keywords()->syncWithoutDetaching($keyword->id);
            }
        }
    }

    /**
     * Add the relationships for the cards multiverse ids
     *
     * @param array $cardData
     * @param Card $card
     */
    public function setMultiverseIds(array $cardData, Card $card) : void
    {
        if (array_key_exists('multiverse_ids', $cardData)) {
            foreach ($cardData['multiverse_ids'] as $id) {
                $card->MultiverseIds()->create([
                    'multiverse_id' => $id,
                ]);
            }
        }
    }

    /**
     * @param array $cardData
     * @param Card $card
     */
    public function setPromoTypes(array $cardData, Card $card) : void
    {
        if (array_key_exists('promo_types', $cardData)) {
            foreach ($cardData['promo_types'] as $typeName) {
                $type = PromoType::firstOrCreate(
                    [
                        'name'  => $typeName,
                    ]
                );
                $card->promoTypes()->syncWithoutDetaching($type->id);
            }
        }
    }

    /**
     * Add/Update the card and its field values
     *
     * @param array $cardData
     * @return Card
     */
    public function updateCard(array $cardData) : Card
    {
        $set = Set::where('setId', '=', $cardData['set_id'])->first();

        echo '    Updating Card: ' . $set->name . ': ' . $this->ifKey($cardData, 'name') . PHP_EOL;
        $card = $set->cards()->firstOrCreate([
            'cardId'            => $this->ifKey($cardData, 'id'),
        ], [
            'arenaId'             => $this->ifKey($cardData, 'arena_id'),
            'languageCode'        => $this->ifKey($cardData, 'lang'),
            'mtgoId'              => $this->ifKey($cardData, 'mtgo_id'),
            'mtgoFoilId'          => $this->ifKey($cardData, 'mtgo_foil_id'),
            'tcgplayerId'         => $this->ifKey($cardData, 'tcgplayer_id'),
            'cardmarketId'        => $this->ifKey($cardData, 'cardmarket_id'),
            'oracleId'            => $this->ifKey($cardData, 'oracle_id'),
            'printsSearchUri'     => $this->ifKey($cardData, 'prints_search_uri'),
            'rulingsUri'          => $this->ifKey($cardData, 'rulings_uri'),
            'scryfallUri'         => $this->ifKey($cardData, 'scryfall_uri'),
            'scryfallApiUri'      => $this->ifKey($cardData, 'uri'),
            'artist'              => $this->ifKey($cardData, 'artist'),
            'booster'             => $this->ifKey($cardData, 'booster'),
            'borderColor'         => $this->ifKey($cardData, 'border_color'),
            'cardBackId'          => $this->ifKey($cardData, 'card_back_id'),
            'collectorNumber'     => $this->ifKey($cardData, 'collector_number'),
            'hasContentWarning'   => $this->ifKey($cardData, 'content_warning'),
            'isOnlineOnly'        => $this->ifKey($cardData, 'digital'),
            'flavorName'          => $this->ifKey($cardData, 'flavor_name'),
            'flavorText'          => $this->ifKey($cardData, 'flavor_text'),
            'frameVersion'        => $this->ifKey($cardData, 'frame'),
            'isFullArt'           => $this->ifKey($cardData, 'full_art'),
            'isHighresImage'      => $this->ifKey($cardData, 'highres_image'),
            'illustrationId'      => $this->ifKey($cardData, 'illustration_id'),
            'imageStatus'         => $this->ifKey($cardData, 'image_status'),
            'printedName'         => $this->ifKey($cardData, 'printed_name'),
            'printedText'         => $this->ifKey($cardData, 'printed_text'),
            'printedTypeLine'     => $this->ifKey($cardData, 'printed_type_line'),
            'isPromo'             => $this->ifKey($cardData, 'promo'),
            'rarity'              => $this->ifKey($cardData, 'rarity'),
            'releaseDate'         => $this->ifKey($cardData, 'released_at'),
            'isReprint'           => $this->ifKey($cardData, 'reprint'),
            'scryfallSetId'       => $this->ifKey($cardData, 'set_id'),
            'scryfallSetUri'      => $this->ifKey($cardData, 'scryfall_set_uri'),
            'isStorySpotlight'    => $this->ifKey($cardData, 'story_spotlight'),
            'isTextless'          => $this->ifKey($cardData, 'textless'),
            'isVariation'         => $this->ifKey($cardData, 'variation'),
            'isVariationOf'       => $this->ifKey($cardData, 'is_variation_of'),
            'watermark'           => $this->ifKey($cardData, 'watermark'),
            'convertedManaCost'   => $this->ifKey($cardData, 'cmc'),
            'edhrecRank'          => $this->ifKey($cardData, 'edhrec_rank'),
            'hasFoil'             => $this->ifKey($cardData, 'foil'),
            'handModifier'        => $this->ifKey($cardData, 'hand_modifier'),
            'lifeModifier'        => $this->ifKey($cardData, 'life_modifier'),
            'loyalty'             => $this->ifKey($cardData, 'loyalty'),
            'manaCost'            => $this->ifKey($cardData, 'mana_cost'),
            'name'                => $this->ifKey($cardData, 'name'),
            'hasNonFoil'          => $this->ifKey($cardData, 'nonfoil'),
            'oracleText'          => $this->ifKey($cardData, 'oracle_text'),
            'isOversized'         => $this->ifKey($cardData, 'oversized'),
            'power'               => $this->ifKey($cardData, 'power'),
            'isReserved'          => $this->ifKey($cardData, 'reserved'),
            'toughness'           => $this->ifKey($cardData, 'toughness'),
            'typeLine'            => $this->ifKey($cardData, 'type_line'),
            'layout'              => $this->ifKey($cardData, 'layout'),
            'imagePngUri'         => $this
                ->ifKey($cardData, 'image_uris')
                ? $this->ifKey($cardData['image_uris'], 'png')
                : null,
            'imageBorderCropUri'  => $this
                ->ifKey($cardData, 'image_uris')
                ? $this->ifKey($cardData['image_uris'], 'border_crop')
                : null,
            'imageArtCropUri'     => $this
                ->ifKey($cardData, 'image_uris')
                ? $this->ifKey($cardData['image_uris'], 'art_crop')
                : null,
            'imageLargeUri'        => $this
                ->ifKey($cardData, 'image_uris')
                ? $this->ifKey($cardData['image_uris'], 'large')
                : null,
            'imageNormalUri'        => $this
                ->ifKey($cardData, 'image_uris')
                ? $this->ifKey($cardData['image_uris'], 'normal')
                : null,
            'imageSmallUri'         => $this
                ->ifKey($cardData, 'image_uris')
                ? $this->ifKey($cardData['image_uris'], 'small')
                : null,
        ]);

        $this->saveImage($card);
        $this->setCardSet($cardData, $card);
        $this->setColorFields($cardData, $card);
        $this->setFrameEffects($cardData, $card);
        $this->setKeywords($cardData, $card);
        $this->setGames($cardData, $card);
        $this->setLegalities($cardData, $card);
        $this->setMultiverseIds($cardData, $card);
        $this->setRelatedObjects($cardData, $card);
        $this->setFaces($cardData, $card);
        $this->setPromoTypes($cardData, $card);

        /// SYMBOLOGY       //
        /// OTHER PRINTINGS... Find In Model
        /// VARIATIONS... Find In Model
        /// RULINGS...
        /// TOKENS...

        //////////////////////
        // ENUMS            //
        //////////////////////
        // rarity           //
        // image_status     //
        // border_color     //
        //////////////////////

        //////////////////////
        // ARRAYS           //
        //////////////////////
        // frame_effects    //
        // multiverse_ids   //
        // all_parts        //
        // card_faces       //
        // keywords         //
        // games            //
        // promo_types      //
        //////////////////////

        //////////////////////
        // OBJECTS          //
        //////////////////////
        // legalities       //
        // image_uris       //
        // prices       ----//
        // purchase_uris----//
        // related_uris ----//
        //////////////////////

        //////////////////////
        // COLORS           //
        //////////////////////
        // color_identity   //
        // color_indicator  //
        // colors           //
        // produced_mana    //
        //////////////////////

        return $card;
    }

    /**
     * @param array $cardData
     * @param Card $card
     */
    public function updatePricing(array $cardData, Card $card) : void
    {
        if (!$prices = $cardData['prices']) {
            return;
        }

        $provider = PriceProvider::firstOrCreate(['name' => 'scryfall'])->id;

        if ($price = $prices['usd']) {
            $card->prices()->updateOrCreate(
                [
                    'card_id'       => $card->id,
                    'provider_id'   => $provider,
                    'foil'          => false,
                ],
                [
                    'price'     => $price,
                ]
            );
        }

        if ($foilPrice = $prices['usd_foil']) {
            $card->prices()->updateOrCreate(
                [
                    'card_id'       => $card->id,
                    'provider_id'   => $provider,
                    'foil'          => true,
                ],
                [
                    'price'     => $foilPrice,
                ]
            );
        }
    }

    /**
     * Add new set and set symbol
     *
     * @param array $set
     */
    public function updateSet(array $set) : void
    {
        echo '    Updating Set: ' . $this->ifKey($set, 'name') . PHP_EOL;
        $currentSet = Set::firstOrCreate([
            'setId'             => $this->ifKey($set, 'id'),
        ], [
            'code'              => $this->ifKey($set, 'code'),
            'mtgoCode'          => $this->ifKey($set, 'mtgo_code'),
            'tcgPlayerGroupId'  => $this->ifKey($set, 'tcgplayer_id'),
            'name'              => $this->ifKey($set, 'name'),
            'type'              => $this->ifKey($set, 'set_type'),
            'releaseDate'       => $this->ifKey($set, 'released_at'),
            'block'             => $this->ifKey($set, 'block'),
            'blockCode'         => $this->ifKey($set, 'block_code'),
            'parentCode'        => $this->ifKey($set, 'parent_set_code'),
            'setSize'           => $this->ifKey($set, 'card_count'),
            'printedSetSize'    => $this->ifKey($set, 'printed_size'),
            'isOnlineOnly'      => $this->ifKey($set, 'digital'),
            'isFoilOnly'        => $this->ifKey($set, 'foil_only'),
            'isNonFoilOnly'     => $this->ifKey($set, 'nonfoil_only'),
            'scryfallUri'       => $this->ifKey($set, 'scryfall_uri'),
            'scryfallApiUri'    => $this->ifKey($set, 'uri'),
            'scryfallSvgUri'    => $this->ifKey($set, 'icon_svg_uri'),
            'scryfallApiSearch' => $this->ifKey($set, 'search_uri'),
        ]);

        if (!$currentSet->svgPath) {
            $filename     = strtolower($currentSet->code . '_icon.svg');
            $publicDir    = 'images/setIcons';
            $iconPath     = $publicDir . '/' . $filename;
            $storageDir   = 'public/' . $publicDir;
            Storage::makeDirectory($storageDir);
            $appDir      = 'app/public';
            $appPath     = $appDir . '/' . $iconPath;
            $storagePath = storage_path($appPath);
            app(DownloadFileAction::class)
                ->saveFile($storagePath, $currentSet->scryfallSvgUri);
            $currentSet->svgPath = $storagePath;
            $currentSet->save();
        }
    }

    /**
     * Fetch all sets and add new ones
     */
    public function updateSets() : void
    {
        $sets = Http::get('https://api.scryfall.com/sets')->json()['data'];
        foreach ($sets as $set) {
            $this->updateSet($set);
        }
    }

    public function updateSymbol(array $symbolData) : void
    {
        echo '    Updating Symbol: ' . $this->ifKey($symbolData, 'symbol') . PHP_EOL;
        $symbol = Symbol::firstOrCreate([
            'symbol'        => $symbolData['symbol'],
        ], [
            'svgUri'                    => $this->ifKey($symbolData, 'svg_uri'),
            'looseVariant'              => $this->ifKey($symbolData, 'loose_variant'),
            'english'                   => $this->ifKey($symbolData, 'english'),
            'transpose'                 => $this->ifKey($symbolData, 'transpose'),
            'representsMana'            => $this->ifKey($symbolData, 'represents_mana'),
            'appearsInManaCosts'        => $this->ifKey($symbolData, 'appears_in_mana_costs'),
            'cmc'                       => $this->ifKey($symbolData, 'cmc'),
            'funny'                     => $this->ifKey($symbolData, 'funny'),
        ]);

        if (!$symbol->svgPath) {
            $filename       = strtolower($symbol->id . '_symbol.svg');
            $publicDir      = 'images/symbols';
            $symbolPath     = $publicDir . '/' . $filename;
            $storageDir     = 'public/' . $publicDir;
            Storage::makeDirectory($storageDir);
            $appDir      = 'app/public';
            $appPath     = $appDir . '/' . $symbolPath;
            $storagePath = storage_path($appPath);
            app(DownloadFileAction::class)
                ->saveFile($storagePath, $symbol->svgUri);
            $symbol->svgPath = 'storage/' . $symbolPath;
            $symbol->save();
        }
    }

    /**
     * Fetch all symbols
     */
    public function updateSymbols() : void
    {
        $symbols = Http::get('https://api.scryfall.com/symbology')->json()['data'];
        foreach ($symbols as $symbol) {
            $this->updateSymbol($symbol);
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
     * Attach an array of legalities to a card
     *
     * @param array $cardData
     * @param Card $card
     */
    private function setLegalities(array $cardData, Card $card) : void
    {
        if ($legalities = $this->ifKey($cardData, 'legalities')) {
            foreach ($legalities as $legalityName => $legalityValue) {
                $card->legalities()->updateOrCreate(
                    ['format' => $legalityName],
                    ['status' => $legalityValue]
                );
            }
        }
    }

    /**
     * @param array $cardData
     * @param Card $card
     */
    private function setRelatedObjects(array $cardData, Card $card) : void
    {
        if (array_key_exists('all_parts', $cardData)) {
            foreach ($cardData['all_parts'] as $partData) {
                $part = RelatedObjects::firstOrCreate(
                    [
                        'object_id' => $partData['id'],
                    ], [
                        'component' => $partData['component'],
                        'name'      => $partData['name'],
                        'type'      => $partData['type_line'],
                        'uri'       => $partData['uri'],
                    ]
                );
                $card->relatedObjects()->syncWithoutDetaching($part->id);
            }
        }
    }
}
