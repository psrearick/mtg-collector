<?php

namespace App\Domain\Collections\Models;

use App\App\Client\Traits\BelongsToUser;
use App\Domain\Base\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Domain\Collections\Models\ImportCard;
use App\Domain\Collections\Models\CardCollection;

class Import extends Model
{
    use SoftDeletes, BelongsToUser;

    public function importCards() : HasMany
    {
        return $this->hasMany(ImportCard::class);
    }

    public function cardCollections() : HasMany
    {
        return $this->hasMany(CardCollection::class);
    }
}