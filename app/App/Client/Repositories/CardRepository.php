<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;
use App\App\Client\Traits\WithLoadAttribute;

class CardRepository extends Repository
{
    use WithLoadAttribute;

    public string $class = '\App\Domain\Cards\Models\Card';

    public string $table = 'cards';

    public function __construct()
    {
        return parent::__construct(app($this->class));
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

        // $this->query =  $this->query->whereIn('sets.id', $sets)
        // ->leftJoin('sets', 'sets.id', '=', $this->getField('set_id'));

        return $this;
    }
//
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
