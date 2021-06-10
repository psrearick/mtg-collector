<?php

namespace App\Domain\Sets\Models;

use App\Domain\Base\Models\Model;
use App\Domain\CardAttributes\Models\Printing;
use App\Domain\Cards\Models\Card;
use App\Domain\Cards\Models\Token;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Set extends Model
{
    /**
     * get all cards in this set
     *
     * @return HasMany
     */
    public function cards() : HasMany
    {
        return $this->hasMany(Card::class);
    }

    /**
     * return this cards printing record
     *
     * @return HasMany
     */
    public function printings() : HasMany
    {
        return $this->hasMany(Printing::class);
    }

    /**
     * get all tokens in this set
     *
     * @return HasMany
     */
    public function tokens() : HasMany
    {
        return $this->hasMany(Token::class);
    }
}
