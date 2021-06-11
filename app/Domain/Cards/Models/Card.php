<?php

namespace App\Domain\Cards\Models;

use App\Domain\CardAttributes\Models\ForeignData;
use App\Domain\CardAttributes\Models\FrameEffect;
use App\Domain\CardAttributes\Models\LeadershipSkill;
use App\Domain\CardAttributes\Models\Legality;
use App\Domain\CardAttributes\Models\Printing;
use App\Domain\CardAttributes\Models\Ruling;
use App\Domain\Prices\Models\Price;
use App\Domain\Prices\Models\PriceProvider;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

class Card extends CardGeneric
{
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

    public function printingSets() : Collection
    {
        return Printing::where('scryfallOracleId', '=', $this->scryfallOracleId)->get();
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
     * Get all variations of this card
     *
     * @return BelongsToMany
     */
    public function variations() : BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'variations', 'card_id', 'variation_id');
    }

    public function getPriceNormalAttribute()
    {
        $provider = PriceProvider::where('name', '=', 'tcgplayer')->first();
        foreach ($this->prices as $price) {
            if ($price->provider_id == $provider->id && !$price->foil) {
                return $price->price;
            }
        }
    }

    public function getPriceFoilAttribute()
    {
        $provider = PriceProvider::where('name', '=', 'tcgplayer')->first();
        foreach ($this->prices as $price) {
            if ($price->provider_id == $provider->id && $price->foil == true) {
                return $price->price;
            }
        }
    }
}
