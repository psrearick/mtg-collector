<?php

namespace App\Domain\Prices\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Price extends Model
{
    protected $casts = [
        'foil' => 'boolean',
    ];

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
        return $this->belongsTo(PriceProvider::class, 'provider_id');
    }
}
