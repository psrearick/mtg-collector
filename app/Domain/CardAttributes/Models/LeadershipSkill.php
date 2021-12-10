<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LeadershipSkill extends Model
{
    // get all cards assigned to this leadership skill
    public function cards() : BelongsToMany
    {
        return $this->belongsToMany(Card::class);
    }
}
