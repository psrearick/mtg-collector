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
    public static function search(Request $request, int $perPage = 15, bool $withImage = false, bool $exact = false) : array
    {
        $results        = [];
        $sets           = [];
        $cards          = app(CardsRepository::class)->select('cards.*');
        $setRequest     = $request->get('set');
        $cardRequest    = $request->get('card');
        $hasResults     = false;

        if ($cardRequest) {
            if ($exact) {
                $cards->equals($cardRequest);
                $hasResults = true;
            } else {
                $term = preg_replace('/[^A-Za-z0-9]/', '', $cardRequest);
                $cards->startsWith($term, 'name_normalized');

//                $names = app(ScryfallSearch::class)->autocomplete($cardRequest);
//                if (count($names)) {
//                    $cards->in('cards.name', $names);
                    $hasResults = true;
//                }
            }
        }

        if ($setRequest) {
            $sets = app(SetsRepository::class);
            if ($exact) {
                $sets->equals($setRequest, 'id');
            } else {
                $sets->like($setRequest);
            }
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
