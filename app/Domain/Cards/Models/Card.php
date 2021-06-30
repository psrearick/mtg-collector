<?php

namespace App\Domain\Cards\Models;

use App\App\Scopes\NotOnlineOnlyScope;
use App\Domain\CardAttributes\Models\Color;
use App\Domain\CardAttributes\Models\ForeignData;
use App\Domain\CardAttributes\Models\FrameEffect;
use App\Domain\CardAttributes\Models\Keyword;
use App\Domain\CardAttributes\Models\LeadershipSkill;
use App\Domain\CardAttributes\Models\Legality;
use App\Domain\CardAttributes\Models\Printing;
use App\Domain\CardAttributes\Models\Ruling;
use App\Domain\Cards\Actions\GetCardFeatures;
use App\Domain\Cards\Actions\GetCardImage;
use App\Domain\Cards\Actions\GetScryfallCard;
use App\Domain\Prices\Models\Price;
use App\Domain\Sets\Models\Set;
use App\Jobs\ImportCardImages;
use App\MultiverseId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

class Card extends CardGeneric
{
    public $asYouType = true;

    protected $casts = [
        'number' => 'int',
    ];

    public function collections() : BelongsToMany
    {
        return $this->belongsToMany(\App\Domain\Collections\Models\Collection::class, 'card_collections')
            ->withPivot(['price_when_added', 'foil', 'description', 'condition', 'quantity']);
    }

    /**
     * get all colors this card is assigned to
     *
     * @return BelongsToMany
     */
    public function colors() : BelongsToMany
    {
        return $this->belongsToMany(Color::class)->withPivot('type');
    }

    /**
     * Get the other face of this card
     *
     * @return BelongsToMany
     */
    public function faces() : BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_faces', 'card_id', 'related_card_id');
    }

    /**
     * Get all foreign data for this card
     *
     * @return HasMany
     */
    public function foreignData() : HasMany
    {
        return $this->hasMany(ForeignData::class);
    }

    /**
     * Get all frame effects for this card
     *
     * @return BelongsToMany
     */
    public function frameEffects() : BelongsToMany
    {
        return $this->belongsToMany(FrameEffect::class, 'card_frame_effect');
    }

    /**
     * @return string
     */
    public function getFeatureAttribute() : string
    {
        $features    = $this->features;
        $allFeatures = [
            $features['frameEffectsString'],
            $features['borderColorString'],
            $features['fullArtString'],
            $features['alternateArtString'],
            $features['foilOnlyString'],
            $features['promoString'],
            $features['textlessString'],
            $features['timeshiftedString'],
            $features['layoutString'],
        ];
        $featureStrings = [];
        foreach ($allFeatures as $feature) {
            if ($feature && strlen($feature) > 0) {
                $featureStrings[] = $feature;
            }
        }

        return implode(', ', $featureStrings);
    }

    public function getFeaturesAttribute() : array
    {
        $featureCollector = new GetCardFeatures($this);

        return [
            'frameEffects'        => $featureCollector->getFrameEffects(),
            'frameEffectsString'  => $featureCollector->getFrameEffectsString(),
            'borderColor'         => $featureCollector->getBorderColor(),
            'borderColorString'   => $featureCollector->getBorderColorString(),
            'fullArt'             => $featureCollector->getFullArt(),
            'fullArtString'       => $featureCollector->getFullArtString(),
            'alternateArt'        => $featureCollector->getAlternateArt(),
            'alternateArtString'  => $featureCollector->getAlternateArtString(),
            'foilOnly'            => $featureCollector->getFoilOnly(),
            'foilOnlyString'      => $featureCollector->getFoilOnlyString(),
            'promo'               => $featureCollector->getPromo(),
            'promoString'         => $featureCollector->getPromoString(),
            'textless'            => $featureCollector->getTextless(),
            'textlessString'      => $featureCollector->getTextlessString(),
            'timeshifted'         => $featureCollector->getTimeshifted(),
            'timeshiftedString'   => $featureCollector->getTimeshiftedString(),
            'layout'              => $featureCollector->getLayout(),
            'layoutString'        => $featureCollector->getLayoutString(),
        ];
    }

    /**
     * @return string
     */
    public function getImageUrlAttribute() : string
    {
        if ($this->imagePath) {
            return asset('storage/' . $this->imagePath);
        }
        $imageUrl = app(GetCardImage::class)->execute($this->scryfallId, 'image');
        ImportCardImages::dispatchAfterResponse($this);

        return $imageUrl;
    }

    /**
//     * @return float|null
     */
//    public function getPriceFoilAttribute() : ?float
//    {
//        return optional(
//            $this->prices
//                ->where('priceProvider.name', '=', 'tcgplayer')
//                ->where('foil', true)
//                ->first()
//        )->price;
//    }

    /**
//     * @return float|null
     */
//    public function getPriceNormalAttribute() : ?float
//    {
//        return optional(
//            $this->prices
//                ->where('priceProvider.name', '=', 'tcgplayer')
//                ->where('foil', false)
//                ->first()
//        )->price;
//    }

    public function getScryfallCardAttribute() : array
    {
        return (new GetScryfallCard())->execute($this->scryfallId);
    }

    /**
     * get all keywords for this card
     *
     * @return BelongsToMany
     */
    public function keywords() : BelongsToMany
    {
        return $this->belongsToMany(Keyword::class);
    }

    /**
     * Get all leadership  skill for this cards
     * @return BelongsToMany
     */
    public function leadershipSkills() : BelongsToMany
    {
        return $this->belongsToMany(LeadershipSkill::class);
    }

    /**
     * Get all legalities for this card
     *
     * @return HasMany
     */
    public function legalities() : HasMany
    {
        return $this->hasMany(Legality::class);
    }

    /**
     * @return HasMany
     */
    public function multiverseIds() : HasMany
    {
        return $this->hasMany(MultiverseId::class);
    }

    /**
     * Get all prices for this card
     *
     * @return HasMany
     */
    public function prices() : HasMany
    {
        return $this->hasMany(Price::class);
    }

    /**
     * Get all printings for this card
     *
     * @return Collection
     */
    public function printings() : Collection
    {
        return Card::where('scryfallOracleId', $this->scryfallOracleId)->with(['set', 'prices', 'prices.priceProvider'])->get();
    }

    /**
     * @return Collection
     */
    public function printingSets() : Collection
    {
        return Printing::where('scryfallOracleId', '=', $this->scryfallOracleId)->with('set')->get();
    }

    /**
     * Get all rulings for this card
     *
     * @return HasMany
     */
    public function rulings() : HasMany
    {
        return $this->hasMany(Ruling::class);
    }

    /**
     * get the set this card is assigned to
     *
     * @return BelongsTo
     */
    public function set() : BelongsTo
    {
        return $this->belongsTo(Set::class);
    }

    /**
     * Get all tokens associated with this card
     *
     * @return BelongsToMany
     */
    public function tokens() : BelongsToMany
    {
        return $this->belongsToMany(Token::class);
    }

    /**
     * @return array
     */
    public function toSearchableArray() : array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'set_code'  => optional($this->set)->code,
            'set_name'  => optional($this->set)->name,
        ];
    }

    /**
     * Get all variations of this card
     *
     * @return BelongsToMany
     */
    public function variations() : BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'variations', 'card_id', 'variation_id');
    }

    protected static function booted() : void
    {
        static::addGlobalScope(new NotOnlineOnlyScope);
    }
}
