<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

class CardGeneric extends Model
{
    /**
     * get all colors this card is assigned to
     *
     * @return MorphToMany
     */
    public function colors() : MorphToMany
    {
        return $this->morphToMany(Color::class, 'colorable');
    }

    /**
     * get all keywords for this card
     *
     * @return MorphToMany
     */
    public function keywords() : MorphToMany
    {
        return $this->morphToMany(Keyword::class, 'keywordable');
    }

    /**
     * get the set this card is assigned to
     *
     * @return MorphToMany
     */
    public function set() : MorphToMany
    {
        return $this->morphToMany(Set::class, 'setable');
    }

    /**
     * get all subtypes for this card
     *
     * @return MorphToMany
     */
    public function subtypes() : MorphToMany
    {
        return $this->morphToMany(Subtype::class, 'subtypeable');
    }

    /**
     * get all supertypes for this card
     *
     * @return MorphToMany
     */
    public function supertypes() : MorphToMany
    {
        return $this->morphToMany(Supertype::class, 'supertypeable');
    }

    /**
     * get all of this card's types
     *
     * @return MorphToMany
     */
    public function types() : MorphToMany
    {
        return $this->morphToMany(Type::class, 'typeable');
    }
}
