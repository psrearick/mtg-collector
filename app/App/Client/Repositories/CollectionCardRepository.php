<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\DataActions\GetCollectionCard;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;

class CollectionCardRepository extends Repository
{
    private GetCollectionCard $getCollectionCard;

    public function __construct(GetCollectionCard $getCollectionCard)
    {
        $this->getCollectionCard = $getCollectionCard;
    }

    public function moveCollection(Collection $origin, Collection $destination, array $items) : void
    {
        $collections = [
            'origin'        => $origin,
            'destination'   => $destination,
        ];

        $collectionCards = $this->getCollectionCards($collections, $items);

        foreach ($collectionCards as $card) {
            if (!$card['origin']) {
                continue;
            }

            if (!$card['destination']) {
                $this->updateCollection($card['origin'], $origin, $destination);

                continue;
            }

            $quantity = $card['origin']->pivot->quantity
                + $card['destination']->pivot->quantity;
            $this->updateQuantity($card['destination'], $destination, $quantity);
            $card['origin']->collections()->detach($origin['id']);
        }
    }

    public function removeCardsFromCollection(Collection $collection, array $cards) : void
    {
        $collectionCards = $this->getCollectionCards(['remove' => $collection], $cards);
        foreach ($collectionCards as $card) {
            $card['remove']->collections()->detach($collection->id);
        }
    }

    private function getCollectionCards(array $collections, array $items) : array
    {
        $collectionCards = [];
        foreach ($items as $item) {
            $collectionCard = [];
            foreach ($collections as $name => $collection) {
                $collectionCard[$name] = $this->getCollectionCard->execute([
                    'collection'    => $collection,
                    'id'            => $item['id'],
                    'finish'        => $item['finish'],
                    'date'          => Carbon::parse($item['acquired_date']),
                    'quantity'      => $item['quantity'],
                ]);
            }
            $collectionCards[] = $collectionCard;
        }

        return $collectionCards;
    }

    private function updateCollection($card, $origin, $destination) : void
    {
        $pivot = $card->pivot->toArray();
        $origin->cards()
                ->newPivotStatementForId($card->id)
                ->where('finish', '=', $pivot['finish'])
                ->where('date_added', '=', $pivot['date_added'])
                ->update([
                    'collection_id' => $destination->id,
                ]);
    }

    private function updateQuantity(Card $card, Collection $collection, int $quantity) : void
    {
        $pivot = $card->pivot->toArray();
        $collection->cards()
            ->newPivotStatementForId($card->id)
            ->where('finish', '=', $pivot['finish'])
            ->where('date_added', '=', $pivot['date_added'])
            ->update([
                'quantity'         => $quantity,
            ]);
    }
}
