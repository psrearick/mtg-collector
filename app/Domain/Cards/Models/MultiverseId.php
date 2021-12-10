<?php

namespace App\Domain\Cards\Models;

use App\Domain\Base\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MultiverseId extends Model
{
    public function Card() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
