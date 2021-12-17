<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\SetCollectionCardsPresenter;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;

class SetCollectionCardsController extends Controller
{
    public function show(Collection $collection, Card $card)
    {
        return response()->json((new SetCollectionCardsPresenter($card))->present());
    }
}
