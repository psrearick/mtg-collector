<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;

class GetCardImage
{
    public function __construct()
    {
    }

    public function execute(?string $scryfallId, string $format = 'normal') : string
    {
        if (!$scryfallId) {
            return '';
        }
        $card = Http::get("https://api.scryfall.com/cards/$scryfallId", ['format' => 'json'])->json();

        if (!$card) {
            return '';
        }

        return $card['image_uris'] ? $card['image_uris'][$format] : '';
    }
}
