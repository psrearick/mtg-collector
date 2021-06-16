<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Repositories\CardsRepository;
use App\App\Client\Repositories\SetsRepository;
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
        $cards = [];
        $sets  = [];

        if ($request->get('set') || $request->get('card')) {
            $setRepository  = new SetsRepository();
            $cardRepository = new CardsRepository();
            $sets           = $setRepository->fromRequest($request, 'set')->get();
            $setIds         = $setRepository->getIds();
            $cards          = $cardRepository
                ->select('cards.*')
                ->fromRequest($request, 'card')
                ->filterOnSets($setIds)->with(['set'])
                ->getPaginated(15);
            foreach ($cards as $card) {
                $card->imagePath = $card->image_url;
            }
        }

        return Inertia::render('Collections/Edit', [
            'collection' => $collection,
            'cards'      => $cards,
            'sets'       => $sets,
        ]);
    }

    /**
     * @return Response
     */
    public function index() : Response
    {
        return Inertia::render('Collections/Index', [
            'collections' => Collection::where('user_id', '=', Auth::id())
                ->whereNull('deleted_at')
                ->get(),
        ]);
    }

    public function show(Collection $collection)
    {
        return Inertia::render('Collections/Show', [
            'collection' => $collection,
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
