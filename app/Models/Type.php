<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Type extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all cards assigned to this type
     *
     * @return MorphToMany
     */
    public function cards(): MorphToMany
    {
        return $this->morphedByMany(Card::class, 'typeable');
    }

    /**
     * Get all tokens assigned to this type
     *
     * @return MorphToMany
     */
    public function tokens(): MorphToMany
    {
        return $this->morphedByMany(Token::class, 'typeable');
    }
}
