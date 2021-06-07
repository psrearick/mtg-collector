<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForeignData extends Model
{
    protected $guarded = ['id'];

    /**
     * get the card that owns this foreign data
     *
     * @return BelongsTo
     */
    public function cards() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
