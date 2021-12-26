<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\CollectionsEditPresenter;
use App\App\Client\Presenters\CollectionsIndexPresenter;
use App\App\Client\Presenters\CollectionsShowPresenter;
use App\App\Client\Repositories\CollectionRepository;
use App\Domain\Collections\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CollectionsController extends Controller
{
    private CollectionRepository $repository;

    public function __construct(CollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function allIndex() : JsonResponse
    {
        $collections = Collection::all()->values();

        return response()->json($collections);
    }

    /**
     * @return Response
     */
    public function create(Request $request) : Response
    {
        return Inertia::render('Collections/Create', [
            'folder' => $request->get('folder') ? (int) $request->get('folder') : null,
        ]);
    }

    public function destroy(Collection $collection) : RedirectResponse
    {
        $this->repository->deleteCollection($collection);

        return Redirect::back();
    }

    /**
     * @param Collection $collection
     * @param Request $request
     * @return Response
     */
    public function edit(Collection $collection, Request $request) : Response
    {
        return Inertia::render('Collections/Edit', [
            'page'            => (new CollectionsEditPresenter($collection, $request))->present(''),
        ]);
    }

    /**
     * @return Response
     */
    public function index() : Response
    {
        return Inertia::render('Collections/Index', (new CollectionsIndexPresenter(null))->present());
    }

    public function show(Collection $collection, Request $request)
    {
        $collectionShow = Collection::with('cards', 'cards.frameEffects', 'cards.prices', 'cards.prices.priceProvider', 'cards.set')->find($collection->id);

        return Inertia::render('Collections/Show', [
            'collection' => (new CollectionsShowPresenter($collectionShow, $request))->present(),
        ]);
    }

    public function store(Request $request) : RedirectResponse
    {
        $form       = $request->get('form');
        $collection = Collection::create([
            'name'          => $form['name'],
            'description'   => $form['description'],
            'user_id'       => Auth::id(),
            'folder_id'     => $form['folder'] ?? null,
            'is_public'     => $form['isPublic'],
        ]);

        return redirect()->action([CollectionsController::class, 'show'], ['collection' => $collection->id]);
    }

    public function update(Collection $collection, Request $request) : RedirectResponse
    {
        $this->repository->updateCollection($collection, $request->all());

        return Redirect::back();
    }
}
