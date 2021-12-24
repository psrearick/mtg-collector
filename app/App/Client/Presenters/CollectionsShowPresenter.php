<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\Repositories\CollectionRepository;
use App\App\Client\Repositories\SetRepository;
use App\Domain\Collections\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;

class CollectionsShowPresenter extends Presenter
{
    protected Collection $collection;

    protected CollectionRepository $collectionRepository;

    protected ?Request $request;

    protected SetRepository $setRepository;

    public function __construct(Collection $collection, Request $request = null)
    {
        $this->collection           = $collection;
        $this->request              = $request;
        $this->setRepository        = app(SetRepository::class);
        $this->collectionRepository = app(CollectionRepository::class);
    }

    public function present() : SupportCollection
    {
        $editPresenter   = (new CollectionsEditPresenter($this->collection, $this->request))->present('', 0);
        $cards           = $editPresenter['cards'];

        return collect([
            'cardQuery'     => optional($this->request)->get('cardSearch') ?: '',
            'setQuery'      => optional($this->request)->get('setSearch') ?: '',
            'sortQuery'     => $editPresenter['sortQuery'],
            'sortOrder'     => $editPresenter['sortOrder'],
            'id'            => $editPresenter['collection']['id'],
            'name'          => $editPresenter['collection']['name'],
            'description'   => $editPresenter['collection']['description'],
            'summary'       => $this->collectionRepository->getSummary($cards),
            'cards'         => $cards->paginate(20)->withQueryString(),
        ]);
    }
}
