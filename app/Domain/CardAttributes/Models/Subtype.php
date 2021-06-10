<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Subtype extends Model
{
    /**
     * get all cards assigned to this subtype
     *
     * @return MorphToMany
     */
    public function cards() : MorphToMany
    {
        return $this->morphedByMany(Card::class, 'subtypeable');
    }
}
