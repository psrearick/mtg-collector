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
    public SetSearch $setSearch;

    public function __construct(SetSearch $search)
    {
        $this->setSearch = $search;
    }

    public function edit(Collection $collection, Request $request) : Response
    {
        $query          = $request->input('query') ?: '';
        $set            = $request->input('set') ?: '';
        $card           = $request->input('card') ?: '';
        $setCards       = [];
        $setSets        = $this->setSearch->search($query, ['id', 'code', 'name']);
        $selectedIndex  = null;

        if ($set) {
            $setCards      = (new SetCollectionsPresenter(Set::find($set), $collection, $card))->present();
            $selectedIndex = $setSets->search(function ($item) use ($set) {
                return $item->id == $set;
            });
        }

        return Inertia::render('Collections/AddFromSet', [
            'setCards'         => $setCards,
            'collection'       => $collection,
            'setSets'          => $setSets,
            'queryString'      => $query,
            'selected'         => $selectedIndex,
            'setCardQuery'     => $card,
        ]);
    }

    public function store()
    {
        //
    }
}
