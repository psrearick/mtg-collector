<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\Domain\Cards\Actions\GetComputed;

class CardsSearchPresenter extends Presenter
{
    protected array $cardSearch;

    protected int  $perPage;

    public function __construct(array $cardSearch, int $perPage)
    {
        $this->cardSearch = $cardSearch;
        $this->perPage    = $perPage;
    }

    public function present() : array
    {
        $cards = $this->cardSearch['cards'];
        if (is_array($cards)) {
            return $this->cardSearch;
        }

        $cards = $cards->where('isOnlineOnly', false)->load(['frameEffects', 'prices', 'prices.priceProvider', 'collections'])->map(function ($card) {
            $foil = 0;
            $nonfoil = 0;
            $card->collections->each(function ($collection) use (&$foil, &$nonfoil) {
                $foil += $collection->pivot->foil ? $collection->pivot->quantity : 0;
                $nonfoil += !$collection->pivot->foil ? $collection->pivot->quantity : 0;
            });

            $computed = new GetComputed($card);
            $computedCard = $computed
                ->add('feature')
                ->add('allPrices')
                ->get();

            return [
                'id'                 => $card->id,
                'name'               => $card->name,
                'card_id'            => $card->id,
                'set'                => $card->set->name,
                'set_id'             => $card->set->id,
                'feature'            => $computedCard->feature,
                'price'              => $computedCard->allPrices['nonfoil'],
                'price_foil'         => $computedCard->allPrices['foil'],
                'price_etched'       => $computedCard->allPrices['etched'],
                'quantity_collected' => $foil + $nonfoil,
                'nonfoil_collected'  => $nonfoil,
                'foil_collected'     => $foil,
            ];
        });

        if ($this->cardSearch['sortQuery']) {
            $sort = [];
            if ($this->cardSearch['sortOrder']) {
                asort($this->cardSearch['sortOrder']);
                foreach ($this->cardSearch['sortOrder'] as $sortField => $order) {
                    if (array_key_exists($sortField, $this->cardSearch['sortQuery'])) {
                        $sort[] = [$sortField, $this->cardSearch['sortQuery'][$sortField]];
                    }
                }
            }
            if (empty($sort)) {
                foreach ($this->cardSearch['sortQuery'] as $sortField => $direction) {
                    $sort[] = [$sortField, $direction];
                }
            }
            $cards = $cards->sortBy($sort);
        }

        if ($this->perPage > 0) {
            $cards                       = $cards->paginate($this->perPage)->withQueryString();
            $this->cardSearch['perPage'] = $this->perPage;
        }
        $this->cardSearch['cards'] = $cards;

        return $this->cardSearch;
    }
}
