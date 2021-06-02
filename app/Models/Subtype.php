<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Subtype extends Model
{
    protected $guarded = ['id'];

    /**
     * get all cards assigned to this subtype
     *
     * @return MorphToMany
     */
    public function cards(): MorphToMany
    {
        return $this->morphedByMany(Card::class, 'subtypeable');
    }
}
