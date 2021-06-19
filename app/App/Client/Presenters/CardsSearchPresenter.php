<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;

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

        $cards = $cards->map(function ($card) {
            $foil = 0;
            $nonfoil = 0;
            $card->collections->each(function ($collection) use (&$foil, &$nonfoil) {
                $foil += $collection->pivot->foil ? $collection->pivot->quantity : 0;
                $nonfoil += !$collection->pivot->foil ? $collection->pivot->quantity : 0;
            });

            return [
                'id'                 => $card->id,
                'card_name'          => $card->name,
                'card_id'            => $card->id,
                'set_name'           => $card->set->name,
                'set_id'             => $card->set->id,
                'feature'            => $card->feature,
                'price'              => $card->price_normal,
                'price_foil'         => $card->price_foil,
                'quantity_collected' => $foil + $nonfoil,
                'nonfoil_collected'  => $nonfoil,
                'foil_collected'     => $foil,
            ];
        });

        if ($this->perPage > 0) {
            $cards                       = $cards->paginate($this->perPage)->withQueryString();
            $this->cardSearch['perPage'] = $this->perPage;
        }
        $this->cardSearch['cards'] = $cards;

        return $this->cardSearch;
    }
}
