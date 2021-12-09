<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\CardsSearchPresenter;
use App\Domain\Cards\Actions\CardSearch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CardSearchController extends Controller
{
    public function index(Request $request, CardSearch $cardSearch) : Response
    {
        $cards = (new CardsSearchPresenter($cardSearch->execute($request, ['perPage' => 0, 'withImage' => false]), 15))->present();

        return Inertia::render('Cards/Index',
            $cards
        );
    }
}
