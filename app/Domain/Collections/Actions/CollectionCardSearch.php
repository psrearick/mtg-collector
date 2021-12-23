<?php

namespace App\Domain\Collections\Actions;

use App\App\Client\DataObjects\CardEditSearchResult;
use App\App\Client\DataObjects\CardSearchResult;
use App\App\Client\Repositories\CardRepository;
use App\App\Client\Repositories\SetRepository;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Str;

class CollectionCardSearch
{
    private CardRepository $cards;

    private string $cardSearch;

    private Collection $collection;

    private SetRepository $sets;

    private string $setSearch;

    public function __construct(Collection $collection, string $cardSearch = '', string $setSearch = '')
    {
        $this->collection = $collection;
        $this->cardSearch = $cardSearch;
        $this->setSearch  = $setSearch;
        $this->cards      = new CardRepository();
        $this->sets       = new SetRepository();
    }

    public function execute(array $searchParameters = [])
    {
        $cardId         = $searchParameters['cardId'] ?? null;
        $exactCard      = $searchParameters['exactCard'] ?? false;
        $exactSet       = $searchParameters['exactSet'] ?? false;
        $paginator      = $searchParameters['paginator'] ?? null;
        $sort           = $searchParameters['sort'] ?? 'name';

        $this->cards->equals('isOnlineOnly', false);

        if ($cardId) {
            return $this->transformCollection(
                $this->addRelations($this->cards->equals('id', $cardId)->get())
            );
        }

        if (!$this->cardSearch && !$this->setSearch) {
            return [];
        }

        if ($this->cardSearch) {
            $this->filterOnCards($exactCard);
        }

        if ($this->setSearch) {
            $this->filterOnSets($exactSet);
        }

        $this->cards->sortBy($sort);

        if ($paginator) {
            return tap($this->cards->getPaginated($paginator), function (AbstractPaginator $paginator) {
                return $this->transformCollection($this->addRelations($paginator->getCollection()));
            });
        }

        return $this->transformCollection($this->addRelations($this->cards->get()));
    }

    private function addRelations(BaseCollection $collection) : BaseCollection
    {
        $collectionId = $this->collection->id;

        return $collection->load([
            'collections' => function ($query) use ($collectionId) {
                $query->where('collections.id', '=', $collectionId);
            },
            'frameEffects',
            'set',
            'prices',
            'prices.priceProvider',
        ]);
    }

    private function filterOnCards($exact) : void
    {
        $search = $this->cardSearch;
        if ($exact) {
            $this->cards->equals('name', $search);
        } else {
            $term = preg_replace('/[^A-Za-z0-9]/', '', $search);
            $this->cards->like($term, 'name_normalized');
        }
    }

    private function filterOnSets($exact) : void
    {
        $search = $this->setSearch;
        if ($exact) {
            $this->sets->equals('id', $search);
        } else {
            $this->sets->like($search);
        }
        $setIds = $this->sets->ids();
        $this->cards->filterOnSets($setIds);
    }

    private function transformCollection(BaseCollection $collection) : BaseCollection
    {
        return $collection->transform(function ($card) {
            $compute = new GetComputed($card);
            $computed = $compute
                ->add('feature')
                ->add('allPrices')
                ->add('image_url')
                ->get();

            $collected   = $card->collections->map(function ($collection) use ($computed) {
                return new CardSearchResult([
                    'id'                => $computed->id,
                    'name'              => $computed->name,
                    'set'               => $computed->set->code,
                    'features'          => $computed->feature,
                    'price'             => $computed->allPrices[$collection->pivot->finish ?? 'nonfoil'] ?: null,
                    'acquired_date'     => (new Carbon($collection->pivot->date_added ?: $collection->pivot->created_at))->toFormattedDateString(),
                    'acquired_price'    => $collection->pivot->price_when_added,
                    'quantity'          => $collection->pivot->quantity,
                    'finish'            => $collection->pivot->finish,
                    'image'             => $computed->image_url,
                    'collector_number'  => $computed->collectorNumber,
                ]);
            });
            $prices     = [];
            $finishes   = $computed->finishes->pluck('name');
            $priceMap   = $card->prices->filter(function ($price) {
                return $price->price && in_array($price->type, [
                    'usd',
                    'usd_foil',
                    'usd_etched',
                ]);
            })->map(function ($price) {
                return [
                    'price' => $price->price,
                    'type'  => $price->type,
                ];
            });

            foreach ($finishes as $finish) {
                $prices[$finish] = match ($finish) {
                    'nonfoil'   => $priceMap->where('type', '=', 'usd')->first()['price'] ?? 0,
                    'foil'      => $priceMap->where('type', '=', 'usd_foil')->first()['price'] ?? 0,
                    'etched'    => $priceMap->where('type', '=', 'usd_etched')->first()['price'] ?? 0,
                    default     => $priceMap->where('type', '=', 'usd')->first()['price'] ?? 0,
                };
            }

            $finishesMap = [];
            $finishes->each(function ($finish) use (&$finishesMap) {
                $finishesMap[$finish] = Str::ucfirst($finish);
            });

            $quantities = [];
            $collected->each(function ($card) use (&$quantities) {
                $finish = $card->finish;
                if (!isset($quantities[$finish])) {
                    $quantities[$finish] = 0;
                }
                $quantities[$finish] += $card->quantity;
            });

            return new CardEditSearchResult([
                'id'                => $computed->id,
                'name'              => $computed->name,
                'set_code'          => $computed->set->code,
                'set_name'          => $computed->set->name,
                'collected'         => $collected->toArray(),
                'features'          => $computed->feature,
                'price'             => $prices,
                'quantities'        => $quantities,
                'finishes'          => $finishesMap,
                'image'             => $computed->image_url,
                'collector_number'  => $computed->collectorNumber,
            ]);
        });
    }
}
