<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Sets\Models\Set;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Printing extends Model
{
    /**
     * @return BelongsTo
     */
    public function Set() : BelongsTo
    {
        return $this->belongsTo(Set::class);
    }
}
