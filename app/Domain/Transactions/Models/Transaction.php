<?php

namespace App\Domain\Transactions\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    public function card() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function collection() : BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
