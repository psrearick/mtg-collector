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
        $perPage = 15;
        $cards   = null;
        if ($request->search) {
            $cards = Card::search($request->search)->paginate($perPage);
            dd($cards);
//            $q = $request->search;

//            $cards = Cards::search($q, function (SearchIndex $algolia, string $query, array $options) {
//                return $algolia->search($query, [
//                    'typoTolerance' => true,
//                    'minWordSizefor1Typo' => 2,
//                ]);
//            })->whereNotNull('name')->orderBy('name')->paginate($perPage);
        }

//        $cards = $cards ?: Card::with('set')->whereNotNull('name')->orderBy('name')->paginate($perPage);
        return Inertia::render('Cards/Index', [
            'cards'     => $cards,
            'cardCount' => $cards->total(),
            'perPage'   => $perPage,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
