<?php

namespace App\Domain\Cards\Actions;

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

        if ($format == 'image') {
            return "https://api.scryfall.com/cards/$scryfallId?format=image";
        }

        $card = Http::get("https://api.scryfall.com/cards/$scryfallId", ['format' => 'json'])->json();

        if (!$card) {
            return '';
        }

        return array_key_exists('image_uris', $card)
            ? (array_key_exists($format, $card['image_uris']) ? $card['image_uris'][$format] : '')
            : '';
    }
}
