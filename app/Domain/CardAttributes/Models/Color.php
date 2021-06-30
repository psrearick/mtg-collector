<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Model;
use App\Domain\Cards\Models\Card;
use App\Domain\Cards\Models\Token;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Color extends Model
{
    /**
     * get all cards assigned to this color
     *
     * @return BelongsToMany
     */
    public function cards() : BelongsToMany
    {
        return $this->belongsToMany(Card::class)->withPivot('type');
    }
//
//    /**
//     * get all tokens assigned to this color
//     *
//     * @return MorphToMany
//     */
//    public function tokens() : MorphToMany
//    {
//        return $this->morphedByMany(Token::class, 'colorable');
//    }
}
