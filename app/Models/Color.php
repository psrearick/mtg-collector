<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Color extends Model
{
    protected $guarded = ['id'];

    /**
     * get all cards assigned to this color
     *
     * @return MorphToMany
     */
    public function cards(): MorphToMany
    {
        return $this->morphedByMany(Card::class, 'colorable');
    }

    /**
     * get all tokens assigned to this color
     *
     * @return MorphToMany
     */
    public function tokens(): MorphToMany
    {
        return $this->morphedByMany(Token::class, 'colorable');
    }
}
