<?php

namespace App\App\Client\Presenters;

use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use App\Domain\Sets\Models\Set;
use Carbon\Carbon;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Str;

class SetCollectionsPresenter
{
    private string $card;

    private Collection $collection;

    private Set $set;

    public function __construct(Set $set, Collection $collection, string $card)
    {
        $this->set        = $set;
        $this->card       = $card;
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
                    'id'                 => $computedCard->id,
                    'number'             => $computedCard->number,
                    'name'               => $computedCard->name,
                    'features'           => $computedCard->feature,
                    'price'              => $computedCard->priceNormal,
                    'price_normal'       => $computedCard->priceNormal,
                    'price_foil'         => $computedCard->priceFoil,
                    'has_foil'           => $computedCard->has_foil,
                    'is_foil'            => false,
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
                $foil = collect([
                    'id'                 => $setCard->get('id'),
                    'number'             => $setCard->get('number'),
                    'name'               => $setCard->get('name') . ' (Foil)',
                    'feature'            => $setCard->get('feature'),
                    'price'              => $setCard->get('price_foil'),
                    'has_foil'           => $setCard->get('has_foil'),
                    'is_foil'            => true,
                    'quantity'           => $collectionCard->get('quantity'),
                    'acquired_price'     => $collectionCard->get('acquired_price'),
                    'acquired_date'      => $collectionCard->get('acquired_date'),
                ]);
                $setCards->push($foil);
                $setCard->put('own_foil', true);
            } else {
                $setCard->put('quantity', $collectionCard->get('quantity'));
                $setCard->put('acquired_price', $collectionCard->get('acquired_price'));
                $setCard->put('acquired_date', $collectionCard->get('acquired_date'));
            }
        }

        $result = $setCards->sortBy([
            ['number', 'asc'],
            ['is_foil', 'asc'],
        ])->values();

        if ($cardTerm = $this->card) {
            $result = $result->filter(function ($card) use ($cardTerm) {
                return Str::contains(Str::lower($card->get('name')), Str::lower($cardTerm));
            });
        }

        return $result;
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
