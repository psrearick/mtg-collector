<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\CollectionsIndexPresenter;
use App\Domain\Collections\Models\Folder;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CollectionFoldersController extends Controller
{
    public function create(Request $request) : Response
    {
        return Inertia::render('Folders/Create', [
            'folder' => $request->get('folder') ? (int) $request->get('folder') : null,
        ]);
    }

    public function destroy(Folder $folder) : RedirectResponse
    {
        $folder->update([
            'deleted_at' => Carbon::now(),
        ]);

        return Redirect::back();
    }

    public function show(Folder $folder) : Response
    {
        $indices = (new CollectionsIndexPresenter($folder))->present();

        return Inertia::render('Folders/Show', [
            'folder'        => $folder,
            'folders'       => $indices['folders'],
            'collections'   => $indices['collections'],
            'totals'        => $indices['totals'],
        ]);
    }

    public function store(Request $request) : RedirectResponse
    {
        $form   = $request->get('form');
        $folder = new Folder([
            'name'          => $form['name'],
            'description'   => $form['description'],
            'user_id'       => Auth::id(),
        ]);

        if ($form['folder']) {
            $folder->appendToNode(Folder::find($form['folder']));
        }

        $folder->save();

        return redirect()->route('collection-folder.show', ['folder' => $folder->id]);
    }

    public function update(Folder $folder, Request $request) : RedirectResponse
    {
        $folder->update([
            'name'          => $request->get('name'),
            'description'   => $request->get('description'),
        ]);

        return Redirect::back();
    }
}
