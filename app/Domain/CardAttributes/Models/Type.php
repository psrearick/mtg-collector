<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use App\Domain\Cards\Models\Token;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Type extends Model
{
    /**
     * Get all cards assigned to this type
     *
     * @return MorphToMany
     */
    public function cards() : MorphToMany
    {
        return $this->morphedByMany(Card::class, 'typeable');
    }

    /**
     * Get all tokens assigned to this type
     *
     * @return MorphToMany
     */
    public function tokens() : MorphToMany
    {
        return $this->morphedByMany(Token::class, 'typeable');
    }
}
