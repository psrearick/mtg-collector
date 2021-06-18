<?php

namespace App\Domain\Collections\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use App\Domain\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Collection extends Model
{
    use SoftDeletes, Searchable;

    /**
     * Get all cards that are part of this collection
     *
     * @return BelongsToMany
     */
    public function cards() : BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_collections')
            ->withPivot(['price_when_added', 'foil', 'description', 'condition', 'quantity', 'date_added', 'created_at']);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
