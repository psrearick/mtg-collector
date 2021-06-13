<?php

namespace App\Domain\Cards\Models;

use App\Actions\GetCardFeatures;
use App\Actions\GetCardImage;
use App\Actions\GetScryfallCard;
use App\Domain\CardAttributes\Models\ForeignData;
use App\Domain\CardAttributes\Models\FrameEffect;
use App\Domain\CardAttributes\Models\LeadershipSkill;
use App\Domain\CardAttributes\Models\Legality;
use App\Domain\CardAttributes\Models\Printing;
use App\Domain\CardAttributes\Models\Ruling;
use App\Domain\Prices\Models\Price;
use App\Domain\Prices\Models\PriceProvider;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

class Card extends CardGeneric
{
    use Searchable;

    public $asYoyType = true;

    public $appends = ['feature', 'features'];

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
     * @return MorphToMany
     */
    public function frameEffects() : MorphToMany
    {
        return $this->morphToMany(FrameEffect::class, 'frame_effectable');
    }

    /**
     * @return string
     */
    public function getFeatureAttribute() : string
    {
        $features = $this->features;
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
        return implode(", ", $featureStrings);
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
        return (new GetCardImage)->execute($this->scryfallId);
    }

    /**
     * @return HigherOrderBuilderProxy|mixed
     */
    public function getPriceFoilAttribute()
    {
        return $this->getPriceOfType(true);
    }

    /**
     * @return HigherOrderBuilderProxy|mixed
     */
    public function getPriceNormalAttribute()
    {
        return $this->getPriceOfType(false);
    }

    public function getScryfallCardAttribute()
    {
        return (new GetScryfallCard())->execute($this->scryfallId);
    }

    /**
     * @param bool $foil
     * @return HigherOrderBuilderProxy|mixed
     */
    public function getPriceOfType(bool $foil)
    {
        $providerPreference = [
            'tcgplayer',
            'cardkingdom',
            'cardmarket',
        ];

        $providers = PriceProvider::all();

        $returnPrice = '';
        foreach ($providerPreference as $providerName) {
            $price = $this->prices()
                ->where('provider_id', '=',
                    $providers->where('name', '=', $providerName)->first()->id)
                ->where('foil', '=', $foil)
                ->first();
            if ($price && $price->price > 0) {
                return $price->price;
            }
        }
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
     * @return BelongsToMany
     */
    public function printings() : BelongsToMany
    {
//        return $this->belongsToMany(Card::class, 'card_card', 'card_id', 'related_card_id');
        return Card::where('scryfallOracleId', $this->scryfallOracleId)->get();
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

    /**
     * @param $query
     * @return mixed
     */
    protected function makeAllSearchableUsing($query)
    {
        return $query->with('set');
    }
}
