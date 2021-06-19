<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\CollectionsIndexPresenter;
use App\App\Client\Presenters\CollectionsShowPresenter;
use App\Domain\Cards\Actions\CardSearch;
use App\Domain\Collections\Models\Collection;
use Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CollectionsController extends Controller
{
    /**
     * @return Response
     */
    public function create() : Response
    {
        return Inertia::render('Collections/Create');
    }

    /**
     * @param Collection $collection
     * @param Request $request
     * @return Response
     */
    public function edit(Collection $collection, Request $request) : Response
    {
        $collectionEdit = Collection::with('cards', 'cards.frameEffects', 'cards.prices', 'cards.prices.priceProvider', 'cards.set')->find($collection->id);

        return Inertia::render('Collections/Edit', [
            'collectionComplete'    => $collectionEdit,
            'collection'            => (new CollectionsShowPresenter($collectionEdit, $request))->present(),
            'search'                => Inertia::lazy(fn () => CardSearch::search($request, 15, true)),
        ]);
    }

    /**
     * @return Response
     */
    public function index() : Response
    {
        return Inertia::render('Collections/Index', [
            'collections' => (new CollectionsIndexPresenter())->present(),
        ]);
    }

    public function show(Collection $collection, Request $request)
    {
        $collectionShow = Collection::with('cards', 'cards.frameEffects', 'cards.prices', 'cards.prices.priceProvider', 'cards.set')->find($collection->id);

        return Inertia::render('Collections/Show', [
            'collection' => (new CollectionsShowPresenter($collectionShow, $request))->present(),
        ]);
    }

    public function store(Request $request)
    {
        $form = $request->get('form');
        $card = Collection::create([
            'name'          => $form['name'],
            'description'   => $form['description'],
            'user_id'       => Auth::id(),
        ]);

        return redirect()->action([CollectionsController::class, 'show'], ['collection' => $card->id]);
    }
}
