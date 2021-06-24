<?php

namespace App\App\Client\Presenters;

use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use App\Domain\Sets\Models\Set;
use Carbon\Carbon;
use Illuminate\Support\Collection as BaseCollection;

class SetCollectionsPresenter
{
    private Collection $collection;

    private Set $set;

    public function __construct(Set $set, Collection $collection)
    {
        $this->set        = $set;
        $this->collection = $collection;
    }

    public function getSetCards() : BaseCollection
    {
        return $this->set->cards
            ->load('frameEffects', 'prices', 'prices.priceProvider')
            ->map(function (Card $card) {
                $computed = new GetComputed($card);
                $computedCard = $computed
                    ->add('feature')
                    ->add('priceNormal')
                    ->add('priceFoil')
                    ->get();

                return collect([
                    'id'                => $computedCard->id,
                    'number'            => $computedCard->number,
                    'name'              => $computedCard->name,
                    'features'          => $computedCard->feature,
                    'price_normal'      => $computedCard->priceNormal,
                    'price_foil'        => $computedCard->priceFoil,
                ]);
            })->sortBy('number')->values();
    }

    public function present() : BaseCollection
    {
        $collectionCards = $this->getCollectionCards();
        $setCards        = $this->getSetCards();

        foreach ($collectionCards as $collectionCard) {
            $setCard = $setCards
                ->where('number', '=', $collectionCard->get('number'))
                ->first();
            if ($collectionCard->get('foil')) {
                $setCard->put('quantity_foil', $collectionCard->get('quantity'));
                $setCard->put('acquired_price_foil', $collectionCard->get('acquired_price'));
                $setCard->put('acquired_date_foil', $collectionCard->get('acquired_date'));
            } else {
                $setCard->put('quantity', $collectionCard->get('quantity'));
                $setCard->put('acquired_price', $collectionCard->get('acquired_price'));
                $setCard->put('acquired_date', $collectionCard->get('acquired_date'));
            }
        }

        return $setCards;
    }

    private function getCollectionCards() : BaseCollection
    {
        return $this->collection->cards
            ->where('set_id', '=', $this->set->id)
            ->map(function (Card $card) {
                return collect([
                    'number'            => $card->number,
                    'foil'              => $card->pivot->foil,
                    'quantity'          => $card->pivot->quantity,
                    'acquired_price'    => $card->pivot->price_when_added,
                    'acquired_date'     => (new Carbon(
                        $card->pivot->date_added ?: $card->pivot->created_at
                    ))->toFormattedDateString(),
                ]);
            })->sortBy('number')->values();
    }
}
