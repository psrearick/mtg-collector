<?php

namespace App\App\Client\Presenters;

use Carbon\Carbon;
use \Illuminate\Support\Collection as ModelCollection;
use App\App\Base\Presenter;
use App\Domain\Collections\Models\Collection;

class CollectionsShowPresenter extends Presenter
{
    protected Collection $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function buildCards(ModelCollection $cards)
    {
        return $cards->map(function ($card) {
            return collect([
                'id'             => $card->id,
                'name'           => $card->name,
                'set'            => $card->set->code,
                'foil'           => $card->pivot->foil,
                'foil_formatted' => $card->pivot->foil ? "(Foil)" : "",
                'features'       => $card->feature,
                'today'          => $card->pivot->foil ? $card->price_foil : $card->price_normal,
                'acquired_date'  => (new Carbon($card->pivot->date_added ?: $card->pivot->created_at))->toFormattedDateString(),
                'acquired_price' => $card->pivot->price_when_added,
            ]);
        });
    }

    public function present() : ModelCollection
    {
        $cards           = $this->buildCards($this->collection->cards);
        $current         = $cards->sum('today');
        $acquired        = $cards->sum('acquired_price');
        $gainLoss        = $current - $acquired;
        $gainLossPercent = $gainLoss / $acquired;

        return collect([
            'id'          => $this->collection->id,
            'name'        => $this->collection->name,
            'description' => $this->collection->description,
            'summary'     => [
                'totalCards'      => $cards->count(),
                'currentValue'    => $current,
                'acquiredValue'   => $acquired,
                'gainLoss'        => $gainLoss,
                'gainLossPercent' => $gainLossPercent,
            ],
            'cards'       => $cards->paginate(20),
            'top_five'     => $cards->sortByDesc('today')->take(5)->values(),
        ]);
    }
}
