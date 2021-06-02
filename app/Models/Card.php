<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Card extends Model
{
    protected $guarded = ['id'];

    /**
     * get all colors this card is assigned to
     *
     * @return MorphToMany
     */
    public function colors(): MorphToMany
    {
        return $this->morphToMany(Color::class, 'colorable');
    }

    /**
     * get all foreign data for this card
     *
     * @return HasMany
     */
    public function foreignData(): HasMany
    {
        return $this->hasMany(ForeignData::class);
    }

    /**
     * get all legalities for this card
     *
     * @return BelongsToMany
     */
    public function legalities(): BelongsToMany
    {
        return $this->belongsToMany(Legality::class);
    }

    /**
     * get all printings for this card
     *
     * @return BelongsToMany
     */
    public function printings(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_card', 'card_id', 'related_card_id');
    }

    /**
     * get all rulings for this card
     *
     * @return HasMany
     */
    public function rulings(): HasMany
    {
        return $this->hasMany(Ruling::class);
    }

    /**
     * get all subtypes for this card
     *
     * @return MorphToMany
     */
    public function subtypes(): MorphToMany
    {
        return $this->morphToMany(Subtype::class, 'subtypeable');
    }

    /**
     * get all supertypes for this card
     *
     * @return MorphToMany
     */
    public function supertypes(): MorphToMany
    {
        return $this->morphToMany(Supertype::class, 'supertypeable');
    }

    /**
     * get all of this card's types
     *
     * @return MorphToMany
     */
    public function types(): MorphToMany
    {
        return $this->morphToMany(Type::class, 'typeable');
    }

    /**
     * get the set this card is assigned to
     *
     * @return MorphToMany
     */
    public function set(): MorphToMany
    {
        return $this->morphToMany(Set::class, 'setable');
    }

    /**
     * get all frame effects for this card
     *
     * @return MorphToMany
     */
    public function frameEffects(): MorphToMany
    {
        return $this->morphToMany(FrameEffect::class, 'frame_effectable');
    }

    /**
     * get all tokens associated with this card
     *
     * @return BelongsToMany
     */
    public function tokens(): BelongsToMany
    {
        return $this->belongsToMany(Token::class);
    }

    /**
     * get all variations of this card
     *
     * @return BelongsToMany
     */
    public function variations(): BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'variations', 'card_id', 'variation_id');
    }

    /**
     * get all keywords for this card
     *
     * @return MorphToMany
     */
    public function keywords(): MorphToMany
    {
        return $this->morphToMany(Keyword::class, 'keywordable');
    }
}
