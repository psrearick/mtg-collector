<?php


namespace App\App\Client\Controllers;


use App\App\Base\Controller;
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
     * @return Response
     */
    public function edit(Collection $collection)
    {
        return Inertia::render('Collections/Edit', [
            'collection' => $collection,
        ]);
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Collections/Index', [
            'collections' => Collection::where('user_id', '=', Auth::id())
                ->whereNull('deleted_at')
                ->get(),
        ]);
    }

    public function show(Collection $collection){
        return Inertia::render('Collections/Show', [
            'collection' => $collection,
        ]);
    }

    public function store(Request $request) {
        $form = $request->get('form');
        $card = Collection::create([
            'name'          => $form['name'],
            'description'   => $form['description'],
            'user_id'       => Auth::id(),
        ]);
        return redirect()->action([CollectionsController::class, 'show'], ['collection' => $card->id]);
    }

}
