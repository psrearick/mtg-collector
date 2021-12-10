<?php

namespace App\Domain\Prices\Models;

use App\Domain\Base\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceProvider extends Model
{
    /**
     * Get all prices provided by this price provider
     *
     * @return HasMany
     */
    public function prices() : HasMany
    {
        return $this->hasMany(Price::class);
    }
}
