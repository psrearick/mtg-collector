<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;

class CardsRepository extends Repository
{
    public string $class = 'App\Domain\Cards\Models\Card';

    public string $table = 'cards';

    public function filterOnSets(array $sets) : CardsRepository
    {
        if ($sets) {
            $this->query = $this->query->whereIn('sets.id', $sets)
                ->leftJoin('sets', 'sets.id', '=', 'cards.set_id');
        }

        return $this;
    }

    public function withoutOnline() : CardsRepository
    {
        $this->query = $this->query->where('cards.isOnlineOnly', '=', false);

        return $this;
    }
}
