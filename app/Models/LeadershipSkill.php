<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LeadershipSkill extends Model
{
    // get all cards assigned to this leadership skill
    public function cards() : BelongsToMany
    {
        return $this->belongsToMany(Card::class);
    }
}
