<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetCardImage
{
    public function __construct()
    {
    }

    public function execute(string $scryfallId, string $format = 'normal') : string
    {
        $card = Http::get("https://api.scryfall.com/cards/$scryfallId", ['format' => 'json'])->json();

        return optional($card['image_uris'])[$format];
    }
}
