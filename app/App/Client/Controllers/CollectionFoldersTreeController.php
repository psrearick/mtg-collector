<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Presenters\CollectionsIndexPresenter;
use App\Domain\Collections\Models\Folder;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CollectionFoldersTreeController extends Controller
{
    public function index() : JsonResponse
    {
        return response()->json(Folder::get()->toTree());
    }
}
