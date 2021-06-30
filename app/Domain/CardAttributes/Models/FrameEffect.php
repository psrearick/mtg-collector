<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class FrameEffect extends Model
{
    /**
     * Get all cards assigned to this frame effect
     *
     * @return MorphToMany
     */
    public function cards() : BelongsToMany
    {
        return $this->belongsToMany(Card::class, 'card_frame_effect');
    }
}
