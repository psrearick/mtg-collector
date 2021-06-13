<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetScryfallCard
{
    public function execute(string $scryfallId) : array
    {
        return Http::get("https://api.scryfall.com/cards/$scryfallId", ['format' => 'json'])->json();
    }
}
