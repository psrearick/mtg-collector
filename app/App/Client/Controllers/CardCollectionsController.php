<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\Domain\Collections\DataActions\CollectionCardDataAction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class CardCollectionsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * Pass request to repository to update
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) : JsonResponse
    {
        try {
            return response()->json(app(CollectionCardDataAction::class)->execute($request->all()));
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()]);
        }
    }
}
