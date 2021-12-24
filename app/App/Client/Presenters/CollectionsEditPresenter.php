<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\DataObjects\CardSearchResult;
use App\App\Client\DataObjects\Collection as CollectionData;
use App\App\Client\Repositories\CardRepository;
use App\App\Client\Repositories\SetRepository;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Str;

class CollectionsEditPresenter extends Presenter
{
    private string $cardQuery;

    private Collection $collection;

    private string $setQuery;

    private array $sortQuery;

    private array $sortOrder;

    private array $filters;

    private SetRepository $setRepository;

    public function __construct(Collection $collection, Request $request)
    {
        $this->collection       = $collection;
        $this->cardQuery        = $request->cardSearch ?? '';
        $this->setQuery         = $request->setSearch ?? '';
        $this->sortQuery        = $request->sort ?? [];
        $this->sortOrder        = $request->sortOrder ?? [];
        $this->filters          = $request->filters ?? [];
        $this->setRepository    = new SetRepository();
    }

    public function present($sort = 'name', $paginate = 20) : array
    {
        $presentedCollection = new CollectionData([
            'id'            => $this->collection->id,
            'name'          => $this->collection->name,
            'description'   => $this->collection->description,
        ]);

        $sortBy = $this->sort($sort);
        $cards = $this->filter($this->buildCards())->sortBy($sortBy['sortBy'])->values();
        if ($paginate && $paginate > 0) {
            $cards = $cards->paginate($paginate)->withQueryString();
        }

        return [
            'collection'    => $presentedCollection->toArray(),
            'cards'         => $cards,
            'cardQuery'     => $this->cardQuery,
            'setQuery'      => $this->setQuery,
            'sortOrder'     => $this->sortOrder,
            'sortQuery'     => $sortBy['sortFields'],
            'filters'       => $this->filters,
        ];
    }

    private function buildCards() : BaseCollection
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
                'price'             => $computed->allPrices[$card->pivot->finish ?? 'nonfoil'] ?: null,
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
                'price'             => $card->price,
                'acquired_date'     => $card->acquired_date,
                'acquired_price'    => $card->acquired_price,
                'quantity'          => $cardCollection->sum('quantity'),
                'finish'            => $card->finish,
                'image'             => $card->image,
            ]);
        })->values();
    }

    private function filter(BaseCollection $cards) : BaseCollection
    {

        if ($filters = $this->filters) {
            foreach ($filters as $filter => $value) {
                if ($filter == 'price') {
                    $min = null;
                    $max = null;
                    if (is_numeric($value)) {
                        $min = $value;
                    }

                    if (is_array($value)) {
                        $min = $value['min'];
                        $max = $value['max'];
                    }

                    if ($min) {
                        $cards = $cards->where('price', '>', $min);
                    }

                    if ($max) {
                        $cards = $cards->where('price', '<', $max);
                    }
                }
            }
        }

        return $cards;
    }

    private function search() : BaseCollection
    {
        $cards      = $this->collection->cards->load('prices', 'frameEffects', 'prices.priceProvider');

        if ($cardQuery = $this->cardQuery) {
            $cards = $cards->filter(function ($card) use ($cardQuery) {
                return Str::contains(Str::lower($card->name), Str::lower($cardQuery));
            });
        }

        if ($setQuery = $this->setQuery) {
            $setIds = $this->setRepository->like($setQuery)->ids();
            $cards  = $cards->whereIn('set_id', $setIds);
        }

        return $cards;
    }

    private function sort(?string $sort) : array
    {
        $sortFields = [];
        if ($sort) {
            $sortFields = [$sort => 'asc'];
        }

        if ($this->sortQuery) {
            $sortFields = $this->sortQuery;
        }

        $sortBy = [];
        if ($this->sortOrder) {
            asort($this->sortOrder);
            foreach ($this->sortOrder as $field => $order) {
                if (array_key_exists($field, $sortFields)){
                    $sortBy[] = [$field, $sortFields[$field]];
                };
            }
        };

        if (empty($sortBy)) {
            foreach ($sortFields as $sortField => $direction) {
                $sortBy[] = [$sortField, $direction];
            }
        }

        return ['sortBy' => $sortBy, 'sortFields' => $sortFields];
    }
}
