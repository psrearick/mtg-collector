<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\Domain\Cards\Actions\CardSearch;
use App\Domain\Cards\Models\Card;
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
        $query = $request->input('query') ?: '';
        $set = $request->input('set') ?: '';

        return Inertia::render('Collections/AddFromSet', [
            'setCards'         => $set ? Set::find($set)->cards : [],
            'collection'    => $collection,
            'setSets'          => SetSearch::search($query, 0, ['id', 'code', 'name']),
            'queryString'   => $query,
        ]);
    }

    public function store()
    {
        //
    }
}
