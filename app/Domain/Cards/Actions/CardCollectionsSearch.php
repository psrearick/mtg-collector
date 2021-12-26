<?php

namespace App\Domain\Cards\Actions;

use App\App\Client\Repositories\CardRepository;
use App\App\Client\Repositories\SetRepository;
use App\Domain\Cards\Actions\GetComputed;
use Illuminate\Support\Facades\Auth;

class CardCollectionsSearch
{
    private CardRepository $cardRepository;

    private SetRepository $setRepository;

    public function __construct(CardRepository $cardRepository, SetRepository $setRepository)
    {
        $this->cardRepository   = $cardRepository;
        $this->setRepository    = $setRepository;
    }

    public function execute(array $searchParameters) : array
    {
        $perPage            = $searchParameters['perPage'] ?? 15;
        $setRequest         = $searchParameters['set'] ?? '';
        $cardRequest        = $searchParameters['card'] ?? '';
        $sortQuery          = $searchParameters['sort'] ?? [];
        $sortOrder          = $searchParameters['sortOrder'] ?? [];
        $userCollections    = Auth::user()->collections->pluck('id')->toArray();

        if ($cardRequest) {
            $term = preg_replace('/[^A-Za-z0-9]/', '', $cardRequest);
            $this->cardRepository->like($term, 'name_normalized');
        }

        if ($setRequest) {
            $this->setRepository->like($setRequest);
            $setIds = $this->setRepository->ids();
            if ($setIds) {
                $this->cardRepository->filterOnSets($setIds);
            }
        }

        $this->cardRepository->filterOnCollections($userCollections);

        $cards = $this->cardRepository->get();
        $cards->load(['collections', 'set', 'frameEffects']);

        $cardMap = $cards->map(function ($card) {
            $quantities = [
                'nonfoil'   => 0,
                'foil'      => 0,
                'etched'    => 0,
                'glossy'    => 0,
                'total'     => 0,
            ];
            $cardCollections = [];
            $card->collections->each(function ($collection) use (&$quantities, &$cardCollections) {
                $quantity = $collection->pivot->quantity;
                $finish = $collection->pivot->finish;
                $quantities[$finish] += $quantity;
                $quantities['total'] += $quantity;
                if (!isset($cardCollections[$collection->id])) {
                    $cardCollections[$collection->id] = [
                        'quantities' => [
                            'nonfoil'   => 0,
                            'foil'      => 0,
                            'etched'    => 0,
                            'glossy'    => 0,
                            'total'     => 0,
                        ],
                    ];
                }
                $cardCollection = &$cardCollections[$collection->id];
                $cardCollection['id'] = $collection->id;
                $cardCollection['name'] = $collection->name;
                $cardCollection['description'] = $collection->description;
                $cardCollection['quantities'][$finish] += $quantity;
                $cardCollection['quantities']['total'] += $quantity;
            });

            if (!$card->set) {
                return;
            }

            $collectionDetails = [];
            foreach ($cardCollections as $collectionId => $collectionDetail) {
                foreach ($collectionDetail['quantities'] as $finish => $amount) {
                    $collectionDetail[$finish] = $amount;
                }
                $collectionDetails[] = $collectionDetail;
            }

            $computed = new GetComputed($card);
            $computedCard = $computed->add('feature')->get();

            return [
                'id'               => $card->id,
                'name'             => $card->name,
                'set'              => $card->set->name,
                'set_code'         => $card->set->code,
                'quantity'         => $quantities['total'],
                'quantity_nonfoil' => $quantities['nonfoil'],
                'quantity_foil'    => $quantities['foil'],
                'quantity_etched'  => $quantities['etched'],
                'collections'      => $collectionDetails,
                'feature'          => $computedCard->feature,
            ];
        })->filter(function ($card) {
            return $card && $card['quantity'] > 0;
        });

        if ($sortQuery) {
            $sort = [];
            if ($sortOrder) {
                asort($sortOrder);
                foreach ($sortOrder as $sortField => $order) {
                    if (array_key_exists($sortField, $sortQuery)) {
                        $sort[] = [$sortField, $sortQuery[$sortField]];
                    }
                }
            }
            if (empty($sort)) {
                foreach ($sortQuery as $sortField => $direction) {
                    $sort[] = [$sortField, $direction];
                }
            }
            $cardMap = $cardMap->sortBy($sort);
        }

        return $cardMap->values()->toArray();
    }
}
