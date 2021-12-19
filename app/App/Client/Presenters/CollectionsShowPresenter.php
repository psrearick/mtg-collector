<?php

namespace App\App\Client\Presenters;

use Illuminate\Support\Collection as ModelCollection;
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

    private function buildCards()
    {
        return $this->search()->map(function ($card) {
            $compute = new GetComputed($card);
            $computed = $compute
                ->add('feature')
                ->add('allPrices')
                ->add('image_url')
                ->get();

            return new CardSearchResult([
                'id'                => $card->id,
                'name'              => $card->name,
                'set'               => $card->set->code,
                'features'          => $computed->feature,
                'today'             => $computed->allPrices[$card->pivot->finish ?? 'nonfoil'] ?: null,
                'acquired_date'     => (new Carbon($card->pivot->date_added ?: $card->pivot->created_at))->toFormattedDateString(),
                'acquired_price'    => $card->pivot->price_when_added,
                'quantity'          => $card->pivot->quantity,
                'finish'            => $card->pivot->finish,
                'image'             => $computed->image_url,
            ]);
        })
        ->filter(function ($card) {
            return $card->quantity > 0;
        })
        ->mapToGroups(function ($card) {
            $key = $card->id . $card->finish;

            return [$key => $card];
        })
        ->map(function ($cardCollection) {
            $cardCollectionSorted = $cardCollection->sortBy('acquired_date');
            $card = $cardCollectionSorted->first();

            return new CardSearchResult([
                'id'                => $card->id,
                'name'              => $card->name,
                'set'               => $card->set,
                'features'          => $card->features,
                'today'             => $card->today,
                'acquired_date'     => $card->acquired_date,
                'acquired_price'    => $card->acquired_price,
                'quantity'          => $cardCollection->sum('quantity'),
                'finish'            => $card->finish,
                'image'             => $card->image,
            ]);
        })->values();
    }

    public function present() : ModelCollection
    {
        $editPresenter = (new CollectionsEditPresenter($this->collection, $this->request))->present('name', 0);
        $cards           = $editPresenter['cards'];

        return collect([
            'cardQuery'     => optional($this->request)->get('cardSearch') ?: '',
            'setQuery'      => optional($this->request)->get('setSearch') ?: '',
            'id'            => $editPresenter['collection']['id'],
            'name'          => $editPresenter['collection']['name'],
            'description'   => $editPresenter['collection']['description'],
            'summary'       => $this->getSummary($cards),
            'cards'         => $cards->paginate(20),
        ]);
    }

    private function getSummary(ModelCollection $cards)
    {
        $count = 0;
        $current = 0;
        $acquired = 0;

        $cards->each(function ($card) use (&$count, &$current, &$acquired) {
            $count += $card->quantity;
            $current += $card->quantity * $card->today;
            $acquired += $card->quantity * $card->acquired_price;
        });

        $gainLoss        = $current - $acquired;
        $gainLossPercent = $gainLoss == 0 ? 0 : 1;
        $gainLossPercent = $acquired != 0 ? $gainLoss / $acquired : $gainLossPercent;

        return [
            'totalCards'      => $count,
            'currentValue'    => $current,
            'acquiredValue'   => $acquired,
            'gainLoss'        => $gainLoss,
            'gainLossPercent' => $gainLossPercent,
            'top_five'        => $cards->sortByDesc('today')->take(5)->values(),
        ];
    }

    protected function search()
    {
        $cards       = $this->collection->cards->load('prices', 'frameEffects', 'prices.priceProvider');
        $setRequest  = optional($this->request)->get('setSearch') ?: null;
        $cardRequest = optional($this->request)->get('cardSearch') ?: null;
        if ($cardRequest) {
            $cards = $cards->filter(function ($card) use ($cardRequest) {
                return Str::contains(Str::lower($card->name), Str::lower($cardRequest));
            });
        }
        if ($setRequest) {
            $setIds   = $this->setRepository->like($setRequest)->ids();
            $cards    = $cards->whereIn('set_id', $setIds);
        }

        return $cards;
    }
}
