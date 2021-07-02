<?php

namespace App\Domain\Mappings\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiMappings extends Model
{
    public function card() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
