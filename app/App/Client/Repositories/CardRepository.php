<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;
use App\App\Client\Traits\WithLoadAttribute;
use App\Domain\Cards\Models\Card;

class CardRepository extends Repository
{
    use WithLoadAttribute;

    public string $class = '\App\Domain\Cards\Models\Card';

    public string $table = 'cards';

    public function __construct(?Card $card = null)
    {
        return parent::__construct($card ?: app($this->class));
    }

    public function filterOnCollections(array $collections) : Repository
    {
        $this->query = $this->query->with(['collections' => function ($query) use ($collections) {
            $query->whereIn('collections.id', $collections);
        }]);

        return $this;
    }

    /**
     * Limit results to those within the specified sets
     *
     * @param array $sets
     * @return Repository
     */
    public function filterOnSets(array $sets) : Repository
    {
        $this->query = $this->query->whereIn($this->getField('set_id'), $sets);

        return $this;
    }

//    /**
//     * Filter out records that are only available in an online format
//     *
//     * @return Repository
//     */
//    public function withoutOnline() : Repository
//    {
//        $this->query =  $this->query->where($this->getField('isOnlineOnly'), '=', false);
//
//        return $this;
//    }
}
