<?php

namespace App\Domain\Collections\DataActions;

use App\Domain\Cards\Models\Card;

class CreateCollectionCard
{
    private GetCollectionCard $getCollectionCard;

    public function __construct(GetCollectionCard $getCollectionCard)
    {
        $this->getCollectionCard = $getCollectionCard;
    }

    public function execute(array $collectionCard) : Card
    {
        $collectionCard['collection']->cards()
            ->attach($collectionCard['id'], [
                // 'price_when_added'  => $collectionCard['price'] ?? null,
                'foil'              => $collectionCard['foil'],
                // 'quantity'          => $collectionCard['quantity'],
                // 'date_added'        => $collectionCard['date'] ?? null,
            ]);

        return $this->getCollectionCard->execute($collectionCard);
    }
}
