<?php

namespace App\Domain\Collections\DataActions;

use App\Domain\Cards\Models\Card;

class GetCollectionCard
{
    public function execute(array $collectionCard) : ?Card
    {
        return $collectionCard['collection']->cards()
            ->where('cards.id', '=', $collectionCard['id'])
            ->where('foil', '=', $collectionCard['foil'])
            // ->where('date_added', '=', $collectionCard['date'])
            ->first();
    }
}
