<?php

namespace App\App\Client\Presenters;

use App\App\Client\DataObjects\CardSearchResult;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;

class SetCollectionsEditPresenter
{
    private Collection $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function present() : array
    {
        $collection             = $this->collection->only(['id', 'name', 'description']);
        $collection['cards']    = $this->collection->cards->load('set', 'prices', 'frameEffects', 'prices.priceProvider')->map(function ($card) {
            return $this->getCard($card);
        });

        return $collection;
    }

    private function getCard(Card $card) : CardSearchResult
    {
        $compute  = new GetComputed($card);
        $computed = $compute
            ->add('feature')
            ->add('allPrices')
            ->get();

        return new CardSearchResult([
            'id'                => $card->id,
            'name'              => $card->name,
            'set'               => $card->set->code,
            'foil'              => $card->pivot->foil,
            'foil_formatted'    => $card->pivot->foil ? '(Foil)' : '',
            'features'          => $computed->feature,
            'today'             => $computed->allPrices[$card->pivot->finish ?? 'nonfoil'] ?: null,
            'acquired_date'     => (new Carbon($card->pivot->date_added ?: $card->pivot->created_at))->toFormattedDateString(),
            'acquired_price'    => $card->pivot->price_when_added,
            'quantity'          => $card->pivot->quantity,
            'finish'            => $card->pivot->finish,
        ]);
    }
}
