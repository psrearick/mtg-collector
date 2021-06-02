<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Supertype extends Model
{
    protected $guarded = ['id'];

    /**
     * get all cards assigned to this supertype
     *
     * @return MorphToMany
     */
    public function cards() : MorphToMany
    {
        return $this->morphedByMany(Card::class, 'supertypeable');
    }
}
