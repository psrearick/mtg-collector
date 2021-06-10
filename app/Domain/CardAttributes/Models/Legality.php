<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Legality extends Model
{
    /**
     * get all cards assigned to this legality
     *
     * @return BelongsToMany
     */
    public function cards() : BelongsToMany
    {
        return $this->belongsToMany(Card::class);
    }
}
