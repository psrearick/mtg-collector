<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Face extends Model
{
    /**
     * @return BelongsTo
     */
    public function card() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
