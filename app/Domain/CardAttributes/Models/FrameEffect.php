<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class FrameEffect extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all cards assigned to this frame effect
     *
     * @return MorphToMany
     */
    public function cards() : MorphToMany
    {
        return $this->morphedByMany(Card::class, 'frame_effectable');
    }
}
