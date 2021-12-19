<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\SetCollectionsEditPresenter;
use App\Domain\Collections\Models\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SetCollectionsController extends Controller
{
    public function edit(Collection $collection, Request $request) : Response
    {
        return Inertia::render('Collections/AddFromSet', [
            'page' => (new SetCollectionsEditPresenter($collection, $request))->present(),
        ]);
    }
}
