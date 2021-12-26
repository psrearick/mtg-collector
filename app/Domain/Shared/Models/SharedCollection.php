<?php

namespace App\Domain\Shared\Models;

use App\App\Client\Traits\BelongsToUser;
use App\Domain\Base\Model;
use App\Domain\Collections\Models\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SharedCollection extends Model
{
    use BelongsToUser, SoftDeletes;

    public function collection() : BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
