<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\CardsSearchPresenter;
use App\App\Client\Presenters\CardsShowPresenter;
use App\Domain\Cards\Actions\CardSearch;
use App\Domain\Cards\Models\Card;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index() : Response
    {
        return Inertia::render('Cards/Index');
    }

    /**
     * Display the specified resource.
     *
     * @param Card $card
     * @return Response
     */
    public function show(Card $card) : Response
    {
        return Inertia::render('Cards/Show', [
            'card' => (new CardsShowPresenter($card))->present(),
        ]
        );
    }
}
