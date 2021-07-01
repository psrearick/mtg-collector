<?php

namespace App\Domain\Symbols\Models;

use App\Domain\Base\Model;
use App\Domain\CardAttributes\Models\Color;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Symbol extends Model
{
    public function colors() : BelongsToMany
    {
        return $this->belongsToMany(Color::class);
    }
}
