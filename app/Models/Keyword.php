<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Keyword extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all cards with this keyword
     *
     * @return MorphToMany
     */
    public function cards(): MorphToMany
    {
        return $this->morphedByMany(Card::class, 'keywordable');
    }

    /**
     * get all tokens assigned to this keyword
     *
     * @return MorphToMany
     */
    public function tokens(): MorphToMany
    {
        return $this->morphedByMany(Token::class, 'keywordable');
    }
}
