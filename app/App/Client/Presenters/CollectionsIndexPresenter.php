<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
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
            $count = $cards->count();
            $value = 0;
            $cards->each(function ($card) use (&$value) {
                $value += $card->pivot->foil ? $card->price_foil : $card->price_normal;
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
