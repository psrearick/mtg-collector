<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\SetCollectionsPresenter;
use App\Domain\Collections\Models\Collection;
use App\Domain\Sets\Actions\SetSearch;
use App\Domain\Sets\Models\Set;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SetCollectionsController extends Controller
{
    public function edit(Collection $collection, Request $request) : Response
    {
        $query    = $request->input('query') ?: '';
        $set      = $request->input('set') ?: '';
        $setCards = [];

        if ($set) {
            $setCards = (new SetCollectionsPresenter(Set::find($set), $collection))->present();
        }

        return Inertia::render('Collections/AddFromSet', [
            'setCards'         => $setCards,
            'collection'       => $collection,
            'setSets'          => SetSearch::search($query, 0, ['id', 'code', 'name']),
            'queryString'      => $query,
        ]);
    }

    public function store()
    {
        //
    }
}
