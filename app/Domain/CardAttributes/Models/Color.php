<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use App\Domain\Cards\Models\Token;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Color extends Model
{
    protected $guarded = ['id'];

    /**
     * get all cards assigned to this color
     *
     * @return MorphToMany
     */
    public function cards() : MorphToMany
    {
        return $this->morphedByMany(Card::class, 'colorable');
    }

    /**
     * get all tokens assigned to this color
     *
     * @return MorphToMany
     */
    public function tokens() : MorphToMany
    {
        return $this->morphedByMany(Token::class, 'colorable');
    }
}
