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
     * @return array
     */
    public static function search(Request $request, int $perPage = 15) : array
    {
        $results     = [];
        $sets        = [];
        $cards       = app(CardsRepository::class)->select('cards.*');
        $setRequest  = $request->get('set');
        $cardRequest = $request->get('card');

        if ($cardRequest) {
//            $cards->startsWith($cardRequest); // Replace this search with scryfall fuzzy search
            $names = app(ScryfallSearch::class)->autocomplete($cardRequest);
            if ($names) {
                $cards->in('cards.name', $names);
            }
        }

        if ($setRequest) {
            $sets   = app(SetsRepository::class)->fromRequest($request, 'set');
            $setIds = $sets->ids();
            $sets   = $sets->get();
            if ($sets) {
                $cards->filterOnSets($setIds);
            }
        }

        if ($cardRequest || $setRequest) {
            $results = $cards->with(['set'])->getPaginated($perPage);
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
