<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PromoType extends Model
{
    /**
     * @return BelongsToMany
     */
    public function Cards() : BelongsToMany
    {
        return $this->belongsToMany(Card::class);
    }
}
