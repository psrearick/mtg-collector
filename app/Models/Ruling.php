<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruling extends Model
{
    protected $guarded = ['id'];

    /**
     * get the card that owns this ruling
     *
     * @return BelongsTo
     */
    public function cards(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
