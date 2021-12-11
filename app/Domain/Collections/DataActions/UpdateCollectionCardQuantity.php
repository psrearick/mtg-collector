<?php

namespace App\Domain\Collections\DataActions;

use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;

class UpdateCollectionCardQuantity
{
    private CreateCollectionCard $createCollectionCard;

    private GetCollectionCard $getCollectionCard;

    public function __construct(GetCollectionCard $getCollectionCard, CreateCollectionCard $createCollectionCard)
    {
        $this->getCollectionCard    = $getCollectionCard;
        $this->createCollectionCard = $createCollectionCard;
    }

    public function execute(array $change) : array
    {
        $collection     = $this->findCollection($change);
        $changeRequest  = [
            'collection'    => $collection,
            'id'            => $change['id'] ?? null,
            'foil'          => $change['foil'] ?? false,
            'date'          => $change['date'] ?? Carbon::today(),
            'quantity'      => $change['change'] ?? $change['quantity'],
        ];
        $collectionCard  = $this->getCard($changeRequest);

        $card = $this->updateCardQuantity($collectionCard, $collection, $changeRequest);

        return [
            'message'        => 'Card quantity was updated',
            'collectionCard' => $this->getCard($changeRequest)->pivot->toArray(),
        ];
    }

    private function findCollection(array $request) : Collection
    {
        return Collection::find($request['collection']);
    }

    private function getCard(array $request) : Card
    {
        $collectionCard  = $this->getCollectionCard->execute($request);

        if (!$collectionCard) {
            return $this->createCollectionCard->execute($request);
        }

        return $collectionCard;
    }

    private function updateCardQuantity(Card $card, Collection $collection, array $request) : Card
    {
        $foil        = $card->pivot->foil;
        $quantity    = $card->pivot->quantity;
        $newQuantity = $quantity + $request['quantity'];
        if ($newQuantity < 0) {
            $newQuantity = 0;
        }

        $now          = Carbon::now()->toDateTimeString();
        $date         = $card->pivot->date_added ?: $now;
        $price        = $card->pivot->price_when_added;
        $computerCard = (new GetComputed($card))->add('priceNormal')->add('priceFoil')->get();
        $priceToday   =
            $foil ?
            $computerCard->priceFoil :
            $computerCard->priceNormal;
        if (!$price) {
            $price = $priceToday;
        }

        $collection->cards()
            ->newPivotStatementForId($card->id)
            ->where('foil', '=', $foil)
            ->update([
                'quantity'         => $newQuantity,
                'price_when_added' => $price,
                'date_added'       => $date,
            ]);

        $card->transactions()->create([
            'collection_id' => $collection->id,
            'price'         => $priceToday,
            'foil'          => $foil,
            'condition'     => '',
            'quantity'      => $request['quantity'],
            'date_added'    => $now,
        ]);

        return $card;
    }
}
