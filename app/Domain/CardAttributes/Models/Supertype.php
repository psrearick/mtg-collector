<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Supertype extends Model
{
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
