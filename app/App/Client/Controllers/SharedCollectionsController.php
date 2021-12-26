<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\CollectionsShowPresenter;
use App\App\Client\Presenters\SharedCollectionsIndexPresenter;
use App\App\Client\Presenters\SharedCollectionsShowPresenter;
use App\App\Scopes\UserScope;
use App\Domain\Collections\Models\Collection;
use App\Domain\Shared\Models\SharedCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class SharedCollectionsController extends Controller
{
    public function destroy(Request $request) : RedirectResponse
    {
        $shared = SharedCollection::find($request->route('shared'));
        $shared->delete();

        return Redirect::route('shared.index');
    }

    public function index(Request $request) : Response
    {
        $shared = (new SharedCollectionsIndexPresenter($request->all()))->present();

        return Inertia::render('Shared/Index', [
            'shared'    => $shared,
        ]);
    }

    public function show(Request $request)
    {
        $shared     = SharedCollection::find($request->route('shared'));
        $collection = $shared->collection
            ->load('user', 'cards', 'cards.frameEffects', 'cards.prices', 'cards.prices.priceProvider', 'cards.set');

        return Inertia::render('Shared/Show', [
            'collection'    => (new CollectionsShowPresenter($collection, $request))->present(),
            'shared'        => $shared,
            'user'          => [
                'name'  => $collection->user->name,
                'id'    => $collection->user->id,
            ],
        ]);
    }

    public function store(Request $request) : RedirectResponse
    {
        $collectionId = $request->get('collection');

        $shared = Auth::user()->sharedCollections()->create([
            'collection_id' => $collectionId,
        ]);

        return Redirect::route('shared.show', ['shared' => $shared]);
    }
}
