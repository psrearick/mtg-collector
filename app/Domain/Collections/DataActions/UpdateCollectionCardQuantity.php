<?php

namespace App\Domain\Collections\DataActions;

use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Actions\CollectionCardSearch;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Support\Facades\Cache;

class UpdateCollectionCardQuantity
{
    private CollectionCardSearch $collectionCardSearch;

    private CreateCollectionCard $createCollectionCard;

    private GetCollectionCard $getCollectionCard;

    public function __construct(
        GetCollectionCard $getCollectionCard,
        CreateCollectionCard $createCollectionCard,
        CollectionCardSearch $collectionCardSearch
    ) {
        $this->getCollectionCard    = $getCollectionCard;
        $this->createCollectionCard = $createCollectionCard;
        $this->collectionCardSearch = $collectionCardSearch;
    }

    public function execute(array $change) : array
    {
        $lock = Cache::lock('update-card-quantity', 10);

        try {
            $lock->block(5);
            $collection     = $this->findCollection($change);
            $changeRequest  = [
                'collection'    => $collection,
                'id'            => $change['id'] ?? null,
                'finish'        => $change['finish'] ?? '',
                'date'          => $change['date'] ?? Carbon::today(),
                'quantity'      => $change['change'] ?? $change['quantity'],
            ];
            $collectionCard  = $this->getCard($changeRequest);

            $this->updateCardQuantity($collectionCard, $collection, $changeRequest);

            $collectionCardUpdated = $this->getCard($changeRequest)->pivot->toArray();
        } catch (LockTimeoutException $e) {
        } finally {
            $lock->release();
        }

        if (!$collectionCardUpdated) {
            return [];
        }

        return [
            'message'        => 'Card quantity was updated',
            'collectionCard' => $collectionCardUpdated,
            'searchCard'     => (new $this->collectionCardSearch($collection))->execute([
                'cardId' => $collectionCardUpdated['card_id'],
            ])->first(),
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
        $finish             = $request['finish'];
        $quantity           = $card->pivot->quantity;
        $requestQuantity    = $request['quantity'];
        $newQuantity        = $quantity + $requestQuantity;
        if ($newQuantity < 0) {
            $requestQuantity = 0 - $quantity;
            $newQuantity     = 0;
        }

        $now          = Carbon::now()->toDateString();
        $date         = $card->pivot->date_added ?: $now;
        $price        = $card->pivot->price_when_added;
        $computerCard = (new GetComputed($card))->add('allPrices')->get();
        $priceToday   = $computerCard->allPrices[$finish];
        if (!$price) {
            $price = $priceToday;
        }

        $collection->cards()
            ->newPivotStatementForId($card->id)
            ->where('finish', '=', $finish)
            ->where('date_added', '=', $date)
            ->update([
                'quantity'         => $newQuantity,
                'price_when_added' => $price,
            ]);

        $card->transactions()->create([
            'collection_id' => $collection->id,
            'price'         => $priceToday,
            'finish'        => $finish,
            'condition'     => '',
            'quantity'      => $requestQuantity,
            'date_added'    => $now,
        ]);

        return $card;
    }
}
