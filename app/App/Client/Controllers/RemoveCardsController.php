<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Repositories\CollectionCardRepository;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class RemoveCardsController extends Controller
{
    private CollectionCardRepository $repository;

    public function __construct(CollectionCardRepository $repository)
    {
        $this->repository = $repository;
    }

    public function destroy(Request $request) : RedirectResponse
    {
        $collection     = Collection::find($request->collection);
        $items          = $request->items;

        $this->repository->removeCardsFromCollection($collection, $items);

        return Redirect::back();
    }
}
