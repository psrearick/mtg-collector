<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Repositories\CollectionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionSearchController extends Controller
{
    protected CollectionRepository $repository;

    public function __construct(CollectionRepository $repository)
    {
        $this->repository = $repository;
    }

    public function store(Request $request) : JsonResponse
    {
        return response()->json($this->repository->searchCollections($request->all()));
    }
}
