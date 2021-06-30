<?php

namespace App;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MultiverseId extends Model
{
    public function Card() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
