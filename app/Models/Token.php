<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Token extends Model
{
    protected $guarded = ['id'];

    /**
     * get all cards that use this token
     *
     * @return BelongsToMany
     */
    public function cards() : BelongsToMany
    {
        return $this->belongsToMany(Card::class);
    }

    /**
     * get all colors this token is assigned to
     *
     * @return MorphToMany
     */
    public function colors() : MorphToMany
    {
        return $this->morphToMany(Color::class, 'colorable');
    }

    /**
     * get all keywords for this token
     *
     * @return MorphToMany
     */
    public function keywords() : MorphToMany
    {
        return $this->morphToMany(Keyword::class, 'keywordable');
    }

    /**
     * get the set this token is assigned to
     *
     * @return MorphToMany
     */
    public function set() : MorphToMany
    {
        return $this->morphToMany(Set::class, 'setable');
    }

    /**
     * get all of this token's types
     *
     * @return MorphToMany
     */
    public function types() : MorphToMany
    {
        return $this->morphToMany(Type::class, 'typeable');
    }
}
