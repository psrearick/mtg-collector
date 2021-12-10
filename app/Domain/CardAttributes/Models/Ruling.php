<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruling extends Model
{
    /**
     * get the card that owns this ruling
     *
     * @return BelongsTo
     */
    public function cards() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
