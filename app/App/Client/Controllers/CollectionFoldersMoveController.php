<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Repositories\CollectionFolderRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CollectionFoldersMoveController extends Controller
{
    private CollectionFolderRepository $repository;

    public function __construct(CollectionFolderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function update(Request $request) : RedirectResponse
    {
        $this->repository->moveItem($request->all());

        return Redirect::back();
    }
}
