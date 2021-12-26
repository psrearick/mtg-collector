<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\DataObjects\CollectionListItem;
use App\App\Client\Repositories\CollectionRepository;
use App\Domain\Collections\Models\Collection;
use App\Domain\Collections\Models\Folder;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Auth;

class CollectionsIndexPresenter extends Presenter
{
    private ?Folder $folder;

    private CollectionRepository $repository;

    public function __construct(?Folder $folder)
    {
        $this->repository = app(CollectionRepository::class);
        $this->folder     = $folder;
    }

    public function present() : array
    {
        if ($this->folder) {
            $indices = $this->getFolderBaseIndices();
        }

        if (!$this->folder) {
            $indices = $this->getBaseIndices();
        }

        $collections     = collect($this->getCollectionIndex($indices['collections']));
        $folders         = collect($this->getFolderIndex($indices['folders']));
        $value           = $collections->sum('value') + $folders->sum('value');
        $count           = $collections->sum('count') + $folders->sum('count');
        $acquired        = $collections->sum('acquired') + $folders->sum('acquired');
        $gainLoss        = $value - $acquired;
        $gainLossPercent = $gainLoss == 0 ? 0 : 1;
        $gainLossPercent = $acquired != 0 ? $gainLoss / $acquired : $gainLossPercent;

        return [
            'collections'   => $collections->sortBy('name')->values(),
            'folders'       => $folders->sortBy('name')->values(),
            'totals'        => [
                'currentValue'              => $value,
                'totalCards'                => $count,
                'acquiredValue'             => $acquired,
                'gainLoss'                  => $gainLoss,
                'gainLossPercent'           => $gainLossPercent,
            ],
        ];
    }

    private function getBaseIndices() : array
    {
        $folders = Folder::withDepth()
            ->where('user_id', '=', Auth::id())
            ->whereNull('deleted_at')->with('collections')
            ->having('depth', '=', 0)
            ->get();

        $collections = Collection::where('user_id', '=', Auth::id())
            ->whereNull('deleted_at')->whereNull('folder_id')->with('cards', 'cards.prices', 'cards.prices.priceProvider')
            ->get();

        return [
            'collections'   => $collections,
            'folders'       => $folders,
        ];
    }

    private function getCollectionIndex(SupportCollection $collections) : array
    {
        $repository      = $this->repository;
        $collectionIndex = [];
        $collections->each(function ($collection) use ($repository, &$collectionIndex) {
            $presenter = (new CollectionsEditPresenter($collection, request()))->present('name', 0);
            $summary = $repository->getSummary($presenter['cards']);

            $collectionIndex[] = (new CollectionListItem([
                'id'            => $collection->id,
                'name'          => $collection->name,
                'description'   => $collection->description,
                'is_public'     => $collection->is_public,
                'type'          => 'collection',
                'value'         => $summary['currentValue'],
                'count'         => $summary['totalCards'],
                'acquired'      => $summary['acquiredValue'],
            ]))->toArray();
        });

        return $collectionIndex;
    }

    private function getFolderBaseIndices() : array
    {
        $collections = $this->getFolderCollections($this->folder);
        $folders     = $this->folder->children->load('descendants');

        return [
            'collections'   => $collections,
            'folders'       => $folders,
        ];
    }

    private function getFolderCollections(Folder $folder) : SupportCollection
    {
        return $folder->collections
            ->whereNull('deleted_at')
            ->load('cards', 'cards.prices', 'cards.prices.priceProvider');
    }

    private function getFolderCounts(Folder $folder) : array
    {
        $index = collect($this->getCollectionIndex($folder->collections));

        return [
            'value'     => $index->sum('value') ?: 0,
            'count'     => $index->sum('count') ?: 0,
            'acquired'  => $index->sum('acquired') ?: 0,
        ];
    }

    private function getFolderIndex(SupportCollection $folders) : array
    {
        $folderIndex = [];
        $folders->each(function ($folder) use (&$folderIndex) {
            $counts = $this->getFolderCounts($folder);
            $descendants = $folder->descendants->load('collections');
            foreach ($descendants as $descendant) {
                $descenantCounts = $this->getFolderCounts($descendant);
                $counts['value'] += $descenantCounts['value'];
                $counts['count'] += $descenantCounts['count'];
                $counts['acquired'] += $descenantCounts['acquired'];
            }

            $folderIndex[] = (new CollectionListItem([
                'id'            => $folder->id,
                'name'          => $folder->name,
                'description'   => $folder->description,
                'type'          => 'folder',
                'value'         => $counts['value'],
                'count'         => $counts['count'],
                'acquired'      => $counts['acquired'],
            ]))->toArray();
        });

        return $folderIndex;
    }
}
