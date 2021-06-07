<?php

namespace App\Domain\CardAttributes\Models;

use App\Domain\Base\Models\Model;
use App\Domain\Cards\Models\Card;
use App\Domain\Cards\Models\Token;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Keyword extends Model
{
    protected $guarded = ['id'];

    /**
     * Get all cards with this keyword
     *
     * @return MorphToMany
     */
    public function cards() : MorphToMany
    {
        return $this->morphedByMany(Card::class, 'keywordable');
    }

    /**
     * get all tokens assigned to this keyword
     *
     * @return MorphToMany
     */
    public function tokens() : MorphToMany
    {
        return $this->morphedByMany(Token::class, 'keywordable');
    }
}
