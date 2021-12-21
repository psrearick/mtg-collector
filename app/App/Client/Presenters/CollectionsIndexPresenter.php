<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\Repositories\CollectionRepository;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Collections\Models\Collection;
use Illuminate\Support\Facades\Auth;

class CollectionsIndexPresenter extends Presenter
{
    private CollectionRepository $repository;

    public function __construct()
    {
        $this->repository = app(CollectionRepository::class);
    }

    public function present()
    {
        $repository  = $this->repository;
        $collections = Collection::where('user_id', '=', Auth::id())
            ->whereNull('deleted_at')->with('cards', 'cards.prices', 'cards.prices.priceProvider')
            ->get();

        return $collections->map(function ($collection) use ($repository) {
            $presenter = (new CollectionsEditPresenter($collection, request()))->present('name', 0);
            $summary = $repository->getSummary($presenter['cards']);

            return [
                'id'           => $collection->id,
                'name'         => $collection->name,
                'description'  => $collection->description,
                'value'        => $summary['currentValue'],
                'count'        => $summary['totalCards'],
            ];
        })->paginate(20)->withQueryString();
    }
}
