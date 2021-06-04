<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the card associated with this price
     * @return BelongsTo
     */
    public function card() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    /**
     * Get the price provider associated with this price
     *
     * @return BelongsTo
     */
    public function priceProvider() : BelongsTo
    {
        return $this->belongsTo(PriceProvider::class);
    }
}
