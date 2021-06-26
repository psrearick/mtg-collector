<?php

namespace App\Domain\Cards\Models;

use App\Domain\Base\Model;
use App\Domain\CardAttributes\Models\Color;
use App\Domain\CardAttributes\Models\Keyword;
use App\Domain\CardAttributes\Models\Subtype;
use App\Domain\CardAttributes\Models\Supertype;
use App\Domain\CardAttributes\Models\Type;
use App\Domain\Sets\Models\Set;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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
     * @return BelongsTo
     */
    public function set() : BelongsTo
    {
        return $this->belongsTo(Set::class);
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
