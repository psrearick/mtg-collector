<?php

namespace App\Domain\Cards\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ScryfallSearch
{
    public function autocomplete(string $query)
    {
        $cardNames = Http::get('https://api.scryfall.com/cards/autocomplete',
        [
            'q' => $query,
        ])->json();
        if (!$cardNames) {
            return [];
        }

        return $cardNames['data'];
    }
}
