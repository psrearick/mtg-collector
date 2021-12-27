<?php

namespace App\Domain\Collections\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Domain\Collections\Models\CardCollection;

class ImportCard extends Model
{
    use SoftDeletes;

    public function import() : BelongsTo
    {
        return $this->belongsTo(Import::class);
    }

    public function card() : BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function cardCollection() : CardCollection
    {
        return $this->cardCollections()->first();
    }

    public function cardCollections() : HasMany
    {
        return $this->hasMany(CardCollection::class);
    }
}