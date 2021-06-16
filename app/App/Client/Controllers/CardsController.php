<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Repositories\CardsRepository;
use App\App\Client\Repositories\SetsRepository;
use App\Domain\Cards\Actions\ScryfallSearch;
use App\Domain\Cards\Models\Card;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CardsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) : Response
    {
        $perPage    = 15;
        $cards      = [];

        if ($request->get('set') || $request->get('card')) {
            $setRepository  = new SetsRepository();
            $cardRepository = new CardsRepository();
            $sets           = $setRepository->fromRequest($request, 'set')->getIds();

            $cards          = $cardRepository
            ->select('cards.*')
            ->fromRequest($request, 'card')
            ->filterOnSets($sets)->with(['set'])
            ->getPaginated($perPage);
        }

        return Inertia::render('Cards/Index', [
            'cards'         => $cards,
            'perPage'       => $perPage,
            'cardQuery'     => $request->get('card'),
            'setQuery'      => $request->get('set'),
        ]);
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
            'card'              => $card,
            'set'               => $card->set,
            'colors'            => $card->colors,
            'keywords'          => $card->keywords,
            'subtypes'          => $card->subtypes,
            'supertypes'        => $card->supertypes,
            'types'             => $card->types,
            'faces'             => $card->faces,
            'frameEffects'      => $card->frameEffects,
            'leadershipSkills'  => $card->leadershipSkills,
            'legalities'        => $card->legalities,
            'printings'         => $card->printings(),
            'printingSets'      => $card->printingSets(),
            'rulings'           => $card->rulings,
            'tokens'            => $card->tokens,
            'variations'        => $card->variations,
            'scryfallCard'      => $card->scryfall_card,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
