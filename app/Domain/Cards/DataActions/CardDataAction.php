<?php

namespace App\Domain\Cards\DataActions;

use App\Domain\Base\DataAction;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Collection;

class CardDataAction extends DataAction
{
    public Card $instance;

    public function __construct(Card $instance)
    {
        parent::__construct();
        $this->instance = $instance;
    }

    public function getOtherPrintings(Card $card) : Collection
    {
        return $this->getPrintings($card)
            ->filter(function ($single) use ($card) {
                return $single->id != $card->id;
            });
    }

    public function getPrintings(Card $card) : Collection
    {
        return $this->instance::where('oracleId', '=', $card->oracleId)
            ->where('cards.isOnlineOnly', '=', false)
            ->get();
    }
}
