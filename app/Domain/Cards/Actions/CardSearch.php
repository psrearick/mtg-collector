<?php

namespace App\Domain\Cards\Actions;

use App\App\Client\Repositories\CardRepository;
use App\App\Client\Repositories\SetRepository;
use App\Domain\Cards\Models\Card;
use Illuminate\Http\Request;

class CardSearch
{
    public CardRepository $cards;

    public function __construct(CardRepository $cards)
    {
        $this->cards = $cards;
    }

    /**
     * @param Request $request
     * @param array $searchParameters
     * @return array
     */
    public function search(Request $request, array $searchParameters) : array
    {
        $perPage        = $searchParameters['perPage'] ?: 15;
        $withImage      = $searchParameters['withImage'];
        $exact          = $searchParameters['exact'];
        $results        = [];
        $sets           = [];
        $setRequest     = $request->get('set');
        $cardRequest    = $request->get('card');
        $hasResults     = false;

        if ($cardRequest) {
            if ($exact) {
                $this->cards->equals('name', $cardRequest);
            } else {
                $term = preg_replace('/[^A-Za-z0-9]/', '', $cardRequest);
                $this->cards->startsWith('name_normalized', $term);
            }
            $this->cards->withoutOnline();
            $hasResults = true;
        }

        if ($setRequest) {
            $sets = app(SetRepository::class);
            if ($exact) {
                $sets->equals($setRequest, 'id');
            } else {
                $sets->like($setRequest);
            }
            $setIds = $sets->ids();
            if ($setIds) {
                $this->cards->filterOnSets($setIds);
                $hasResults = true;
            }
        }

        if ($hasResults) {
            $this->cards->with(['set']);

            if ($withImage) {
                $this->cards->loadAttribute(['image_url']);
            }
            $results = $this->cards->get();
            if ($perPage > 0) {
                $results = $results->paginate($perPage)->withQueryString();
            }
        }

        return [
            'cards'         => $results,
            'perPage'       => $perPage,
            'cardQuery'     => $cardRequest,
            'setQuery'      => $setRequest,
            'sets'          => $sets,
        ];
    }
}
