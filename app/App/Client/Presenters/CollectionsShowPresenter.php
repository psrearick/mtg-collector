<?php

namespace App\App\Client\Presenters;

use App\App\Client\Repositories\SetsRepository;
use Illuminate\Http\Request;
use \Illuminate\Support\Collection as ModelCollection;
use App\App\Base\Presenter;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CollectionsShowPresenter extends Presenter
{
    protected Collection $collection;

    protected ?Request $request;


    public function __construct(Collection $collection, Request $request = null)
    {
        $this->collection = $collection;
        $this->request = $request;
    }

    public function buildCards()
    {
        return $this->search()->map(function ($card) {
            return collect([
                'id'             => $card->id,
                'name'           => $card->name,
                'set'            => $card->set->code,
                'foil'           => $card->pivot->foil,
                'foil_formatted' => $card->pivot->foil ? '(Foil)' : '',
                'features'       => $card->feature,
                'today'          => $card->pivot->foil ? $card->price_foil : $card->price_normal,
                'acquired_date'  => (new Carbon($card->pivot->date_added ?: $card->pivot->created_at))->toFormattedDateString(),
                'acquired_price' => $card->pivot->price_when_added,
            ]);
        });
    }

    public function present() : ModelCollection
    {
        $cards           = $this->buildCards();
        $current         = $cards->sum('today');
        $acquired        = $cards->sum('acquired_price');
        $gainLoss        = $current - $acquired;
        $gainLossPercent = $gainLoss != 0 ? $gainLoss / $acquired : 0;

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
            'cards'        => $cards->paginate(20),
            'top_five'      => $cards->sortByDesc('today')->take(5)->values(),
            'cardQuery'    => $this->request->get('card') ?: "",
            'setQuery'     => $this->request->get('set') ?: "",
        ]);
    }

    protected function search()
    {
        $cards       = $this->collection->cards;
        $setRequest  = $this->request->get('set');
        $cardRequest = $this->request->get('card');
        if ($cardRequest) {
            $cards = $cards->filter(function($card) use ($cardRequest) {
                return Str::startsWith(strtolower($card->name), strtolower($cardRequest));
            });
        }
        if ($setRequest) {
            $sets   = app(SetsRepository::class)->fromRequest($this->request, 'set');
            $setIds = $sets->ids();
            $cards = $cards->whereIn('set_id', $setIds);
        }

        return $cards;
    }
}
