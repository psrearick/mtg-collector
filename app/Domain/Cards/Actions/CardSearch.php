<?php

namespace App\Domain\Cards\Actions;

use App\App\Client\Repositories\CardRepository;
use App\App\Client\Repositories\SetsRepository;
use Illuminate\Http\Request;

class CardSearch
{
    /**
     * @param Request $request
     * @param int $perPage
     * @param bool $withImage
     * @param bool $exact
     * @return array
     */
    public static function search(Request $request, int $perPage = 15, bool $withImage = false, bool $exact = false) : array
    {
        $results        = [];
        $sets           = [];
        $cards          = new CardRepository;
        $setRequest     = $request->get('set');
        $cardRequest    = $request->get('card');
        $hasResults     = false;

        if ($cardRequest) {
            if ($exact) {
                $cards->equals('name', $cardRequest);
            } else {
                $term = preg_replace('/[^A-Za-z0-9]/', '', $cardRequest);
                $cards->startsWith('name_normalized', $term);
            }
            $cards->withoutOnline();
            $hasResults = true;
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
            $results = $cards->get();
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
