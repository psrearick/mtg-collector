<?php

namespace App\Domain\Collections\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CardCollection extends Pivot
{
    public function import() : BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}
