<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceProvider extends Model
{
    protected $guarded = ['id'];

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
