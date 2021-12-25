<?php

namespace App\Domain\Collections\Models;

use App\App\Client\Traits\BelongsToUser;
use App\Domain\Base\Model;
use App\Domain\Collections\Models\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Folder extends Model
{
    use NodeTrait, SoftDeletes, BelongsToUser;

    public function collections() : HasMany
    {
        return $this->hasMany(Collection::class);
    }
}
