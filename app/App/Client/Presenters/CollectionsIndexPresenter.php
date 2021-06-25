<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Collections\Models\Collection;
use Illuminate\Support\Facades\Auth;

class CollectionsIndexPresenter extends Presenter
{
    public function present()
    {
        $collections = Collection::where('user_id', '=', Auth::id())
            ->whereNull('deleted_at')->with('cards', 'cards.prices', 'cards.prices.priceProvider')
            ->get();

        return $collections->map(function ($collection) {
            $cards = $collection->cards;
            $count = $cards->sum('pivot.quantity');
            $value = 0;
            $cards->each(function ($card) use (&$value) {
                $computed = new GetComputed($card);
                $computedCard = $computed
                    ->add('priceNormal')
                    ->add('priceFoil')
                    ->get();
                $value += $card->pivot->foil ? $computedCard->priceFoil : $computedCard->priceNormal;
            });

            return [
                'id'           => $collection->id,
                'name'         => $collection->name,
                'description'  => $collection->description,
                'value'        => $value,
                'count'        => $count,
            ];
        })->paginate(20)->withQueryString();
    }
}
