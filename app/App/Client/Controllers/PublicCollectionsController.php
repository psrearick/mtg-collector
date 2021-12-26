<?php

namespace App\App\Client\Controllers;

use App\App\Client\Presenters\CollectionsShowPresenter;
use App\App\Scopes\UserScope;
use App\Domain\Collections\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;

class PublicCollectionsController extends Controller
{
    public function show(Request $request)
    {
        $collection = Collection::withoutGlobalScope(UserScope::class)
            ->with('user', 'cards', 'cards.frameEffects', 'cards.prices', 'cards.prices.priceProvider', 'cards.set')
            ->find($request->route('collection'));

        return Inertia::render('Public/Collections/Show', [
            'collection' => (new CollectionsShowPresenter($collection, $request))->present(),
            'user'       => $collection->user->name,
        ]);
    }
}
