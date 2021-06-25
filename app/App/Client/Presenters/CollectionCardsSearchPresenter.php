<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\Domain\Cards\Actions\GetComputed;

class CollectionCardsSearchPresenter extends Presenter
{
    protected array $cardSearch;

    protected int $page;

    protected string $pageName;

    protected int  $perPage;

    public function __construct(array $cardSearch, int $perPage, int $page, string $pageName)
    {
        $this->cardSearch = $cardSearch;
        $this->perPage    = $perPage;
        $this->page       = $page ?: 1;
        $this->pageName   = $pageName ?: 'page';
    }

    public function present() : array
    {
        $cards = $this->cardSearch['cards'];
        if (is_array($cards)) {
            return $this->cardSearch;
        }

        $cards = $cards->load(['frameEffects', 'prices', 'prices.priceProvider', 'collections'])->map(function ($card) {
            $foil = 0;
            $nonfoil = 0;
            $card->collections->each(function ($collection) use (&$foil, &$nonfoil) {
                $foil += $collection->pivot->foil ? $collection->pivot->quantity : 0;
                $nonfoil += !$collection->pivot->foil ? $collection->pivot->quantity : 0;
            });

            $computed = new GetComputed($card);
            $computedCard = $computed
                ->add('feature')
                ->add('priceNormal')
                ->add('priceFoil')
                ->get();

            return [
                'id'                 => $card->id,
                'name'               => $card->name,
                'set_name'           => $card->set->name,
                'set_id'             => $card->set->id,
                'feature'            => $computedCard->feature,
                'hasNonFoil'         => $card->hasNonFoil,
                'hasFoil'            => $card->hasFoil,
                'price'              => $computedCard->priceNormal,
                'price_foil'         => $computedCard->priceFoil,
                'quantity_collected' => $foil + $nonfoil,
                'nonfoil_collected'  => $nonfoil,
                'foil_collected'     => $foil,
                'image_url'          => $card->image_url,
            ];
        });

        if ($this->perPage > 0) {
            $cards                       = $cards->values()->paginate($this->perPage, null, null, $this->pageName)->withQueryString();
            $this->cardSearch['perPage'] = $this->perPage;
        }
        $this->cardSearch['cards'] = $cards;

        return $this->cardSearch;
    }
}
