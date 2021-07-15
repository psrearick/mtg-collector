<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;

class CardsRepository extends Repository
{
    public string $class = 'App\Domain\Cards\Models\Card';

    public string $table = 'cards';

    public function filterOnSets(array $sets) : CardsRepository
    {
        $this->query = $this->query->whereIn('sets.id', $sets)
            ->leftJoin('sets', 'sets.id', '=', $this->getField('set_id'));

        return $this;
    }

    public function withoutOnline() : CardsRepository
    {
        $this->query = $this->query->where($this->getField('isOnlineOnly'), '=', false);

        return $this;
    }
}
