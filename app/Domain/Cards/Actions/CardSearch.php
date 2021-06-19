<?php

namespace App\Domain\Cards\Actions;

use App\App\Client\Repositories\CardsRepository;
use App\App\Client\Repositories\SetsRepository;
use Illuminate\Http\Request;

class CardSearch
{
    /**
     * @param Request $request
     * @param int $perPage
     * @param bool $withImage
     * @return array
     */
    public static function search(Request $request, int $perPage = 15, bool $withImage = false) : array
    {
        $results        = [];
        $sets           = [];
        $cards          = app(CardsRepository::class)->select('cards.*');
        $setRequest     = $request->get('set');
        $cardRequest    = $request->get('card');
        $hasResults     = false;

        if ($cardRequest) {
//            $cards->startsWith($cardRequest); // Replace this search with scryfall fuzzy search
            $names = app(ScryfallSearch::class)->autocomplete($cardRequest);
            if (count($names)) {
                $cards->in('cards.name', $names);
                $cards->with(['frameEffects', 'prices', 'prices.priceProvider', 'collections']);
                $hasResults = true;
            }
        }

        if ($setRequest) {
            $sets   = app(SetsRepository::class)->like($setRequest);
            $setIds = $sets->ids();
            if ($setIds) {
                $cards->filterOnSets($setIds);
                $hasResults = true;
            }
        }

        if ($hasResults) {
            $cards->with(['set']);
            if ($withImage) {
                $cards->loadAttribute(['image_url']);
            }

            if ($perPage > 0) {
                $results = $cards->getPaginated($perPage);
            } else {
                $results = $cards->get();
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
