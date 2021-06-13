<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
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
     * @return Response
     */
    public function index(Request $request)
    {
        $perPage    = 15;
        $cards      = [];
        $query      = '';
        if ($request->q) {
            $query   = $request->q;
            $page    = $request->page ? (int) $request->page : null;
            $results = Card::search($query)->query(function ($builder) {
                $builder->with('set');
            })->get();
            $cards = $results->paginate($perPage, $results->count(), $page)->withQueryString();
        }
        $cards = $cards ?: Card::with('set')->whereNotNull('name')->orderBy('name')->paginate($perPage);

        return Inertia::render('Cards/Index', [
            'cards'         => $cards,
            'perPage'       => $perPage,
            'query'         => $query,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Card $card
     * @return Response
     */
    public function show(Card $card): Response
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
            'printings'         => $card->printings,
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
