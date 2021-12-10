<?php

namespace App\App\Client\Presenters;

use \Illuminate\Support\Collection as ModelCollection;
use App\App\Base\Presenter;
use App\App\Client\DataObjects\CardSearchResult;
use App\App\Client\Repositories\SetRepository;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CollectionsShowPresenter extends Presenter
{
    protected Collection $collection;

    protected ?Request $request;

    protected $setRepository;

    public function __construct(Collection $collection, Request $request = null)
    {
        $this->collection       = $collection;
        $this->request          = $request;
        $this->setRepository    = app(SetRepository::class);
    }

    public function buildCards()
    {
        return $this->search()->map(function ($card) {
            $computed = new GetComputed($card);
            $computedCard = $computed
                ->add('feature')
                ->add('priceNormal')
                ->add('priceFoil')
                ->get();
                
            return new CardSearchResult([
                'id'             => $card->id,
                'name'           => $card->name,
                'set'            => $card->set->code,
                'foil'           => $card->pivot->foil,
                'foil_formatted' => $card->pivot->foil ? '(Foil)' : '',
                'features'       => $computedCard->feature,
                'today'          => $card->pivot->foil ? $computedCard->priceFoil : $computedCard->priceNormal,
                'acquired_date'  => (new Carbon($card->pivot->date_added ?: $card->pivot->created_at))->toFormattedDateString(),
                'acquired_price' => $card->pivot->price_when_added,
                'quantity'       => $card->pivot->quantity,
            ]);
        })
        ->filter(function ($card) {
            return $card->quantity > 0;
        });
    }

    public function present() : ModelCollection
    {
        $cards           = $this->buildCards();
        $cardsSorted     = $cards->sortBy('name');
        $current         = $cards->sum('today');
        $acquired        = $cards->sum('acquired_price');
        $gainLoss        = $current - $acquired;
        $gainLossPercent = $gainLoss == 0 ? 0 : 1;
        $gainLossPercent = $acquired != 0 ? $gainLoss / $acquired : $gainLossPercent;

        return collect([
            'id'          => $this->collection->id,
            'name'        => $this->collection->name,
            'description' => $this->collection->description,
            'summary'     => [
                'totalCards'      => $cards->sum('quantity'),
                'currentValue'    => $current,
                'acquiredValue'   => $acquired,
                'gainLoss'        => $gainLoss,
                'gainLossPercent' => $gainLossPercent,
            ],
            'cards'         => $cardsSorted->paginate(20),
            'top_five'      => $cards->sortByDesc('today')->take(5)->values(),
            'cardQuery'     => $this->request->get('cardSearch') ?: '',
            'setQuery'      => $this->request->get('setSearch') ?: '',
        ]);
    }

    protected function search()
    {
        $cards       = $this->collection->cards;
        $setRequest  = $this->request->get('setSearch');
        $cardRequest = $this->request->get('cardSearch');
        if ($cardRequest) {
            $cards = $cards->filter(function ($card) use ($cardRequest) {
                return Str::contains(Str::lower($card->name), Str::lower($cardRequest));
            });
        }
        if ($setRequest) {
            $setIds   = $this->setRepository->like($setRequest)->ids();
            $cards  = $cards->whereIn('set_id', $setIds);
        }

        return $cards;
    }
}
