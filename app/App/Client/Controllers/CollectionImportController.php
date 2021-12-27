<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\Domain\Cards\Actions\MatchImportToCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Domain\Collections\DataActions\ImportCardData;
use App\Domain\Collections\Models\Collection;
use App\Domain\Collections\DataActions\CollectionCardDataAction;

class CollectionImportController extends Controller
{
    public function store(Request $request) : Response
    {
        set_time_limit(300);
        $cards = app(ImportCardData::class)->execute($request);
        $mappedCards = $cards->map(function ($card) {
            return [
                'import' => $card,
                'results' => app(MatchImportToCard::class)->execute($card),
            ];
        })->filter(function ($map) {
            return $map['results']->count() > 0;
        });
    
        return Inertia::render('Collections/Import', [
            'cards' => $mappedCards,
            'collection' => (int) $request->get('collection'),
        ]);
    }

    public function update($collectionId, Request $request) : RedirectResponse
    {
        set_time_limit(300);
        foreach ($request->get('cards') as $card) {
            app(CollectionCardDataAction::class)->execute([
                'collection' => $collectionId,
                'quantity' => $card['quantity'],
                'finish'    => $card['finish'],
                'id'    => $card['name'],
            ]);
        }

        return Redirect::route('collections.show', [
            'collection' => $collectionId,
        ]);
    }
}