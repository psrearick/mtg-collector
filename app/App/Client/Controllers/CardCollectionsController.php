<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\App\Client\Repositories\CollectionCardRepository;
use App\Domain\Cards\Models\Card;
use App\Domain\Collections\DataActions\CollectionCardDataAction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardCollectionsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * Pass request to repository to update
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request) : Response
    {
        return app(CollectionCardDataAction::class)->updateCollectionCard($request);
//        return app(CollectionCardRepository::class)->updateCollectionCard($request);
    }
}
