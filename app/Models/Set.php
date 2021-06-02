<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    protected $guarded = ['id'];

    /**
     * get all cards in this set
     *
     * @return HasMany
     */
    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * get all tokens in this set
     *
     * @return HasMany
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class);
    }
}
