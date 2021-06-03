<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Card extends CardGeneric
{
    protected $guarded = ['id'];

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
     * get all foreign data for this card
     *
     * @return HasMany
     */
    public function foreignData() : HasMany
    {
        return $this->hasMany(ForeignData::class);
    }

    /**
     * get all frame effects for this card
     *
     * @return MorphToMany
     */
    public function frameEffects() : MorphToMany
    {
        return $this->morphToMany(FrameEffect::class, 'frame_effectable');
    }

    /**
     * get all leadership  skill for this cards
     * @return BelongsToMany
     */
    public function leadershipSkills() : BelongsToMany
    {
        return $this->belongsToMany(LeadershipSkill::class);
    }

    /**
     * get all legalities for this card
     *
     * @return HasMany
     */
    public function legalities() : HasMany
    {
        return $this->hasMany(Legality::class);
    }

    /**
     * get all printings for this card
     *
     * @return BelongsToMany
     */
    public function printings() : BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_card', 'card_id', 'related_card_id');
    }

    /**
     * get all rulings for this card
     *
     * @return HasMany
     */
    public function rulings() : HasMany
    {
        return $this->hasMany(Ruling::class);
    }

    /**
     * get all tokens associated with this card
     *
     * @return BelongsToMany
     */
    public function tokens() : BelongsToMany
    {
        return $this->belongsToMany(Token::class);
    }

    /**
     * get all variations of this card
     *
     * @return BelongsToMany
     */
    public function variations() : BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'variations', 'card_id', 'variation_id');
    }
}
