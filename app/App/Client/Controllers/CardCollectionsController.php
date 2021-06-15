<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Repositories\CollectionCardRepository;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardCollectionsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Card $card
     * @return Response
     */
    public function show(Card $card) : Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Pass request to repository to update
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) : Response
    {
        return (new CollectionCardRepository())->updateCollectionCard($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
