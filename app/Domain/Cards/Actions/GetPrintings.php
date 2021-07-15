<?php

namespace App\Domain\Cards\Actions;

use App\App\Client\Repositories\CardRepository;
use App\Domain\Cards\Models\Card;
use Illuminate\Support\Collection;

class GetPrintings
{
    public static function getOtherPrintings(Card $card) : Collection
    {
        $cards = self::getPrintings($card);

        return $cards->filter(function ($singleCard) use ($card) {
            return $singleCard->id != $card->id;
        });
    }

    public static function getPrintings(Card $card) : Collection
    {
        $cardBuilder = new CardRepository($card);
        $cardBuilder->equals('oracleId', $card->oracleId);
        $cardBuilder->withoutOnline();

        return $cardBuilder->get();
    }
}
