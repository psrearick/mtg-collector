<?php

namespace App\Domain\Collections\DataActions;

use App\Domain\Cards\Models\Card;
use Carbon\Carbon;

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
                'finish'        => $collectionCard['finish'],
                'date_added'    => $collectionCard['date'] ?? Carbon::now()->toDateString(),
            ]);

        return $this->getCollectionCard->execute($collectionCard);
    }
}
