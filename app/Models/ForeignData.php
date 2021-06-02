<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForeignData extends Model
{
    protected $guarded = ['id'];

    /**
     * get the card that owns this foreign data
     *
     * @return BelongsTo
     */
    public function cards(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
