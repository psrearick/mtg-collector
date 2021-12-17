<?php

namespace App\App\Client\Controllers;

use App\App\Base\Controller;
use App\Domain\Collections\Actions\CollectionCardSearch;
use App\Domain\Collections\Models\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CollectionsEditSearchController extends Controller
{
    public function store(Collection $collection, Request $request) : JsonResponse
    {
        return response()->json([
            'cards' => (new CollectionCardSearch(
                $collection,
                $request->get('card') ?: '',
                $request->get('set') ?: ''))
            ->execute(['paginator' => $request->get('paginator') ?: null]),
        ]);
    }
}
