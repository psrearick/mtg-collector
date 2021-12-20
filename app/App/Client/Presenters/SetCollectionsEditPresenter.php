<?php

namespace App\App\Client\Presenters;

use App\App\Client\DataObjects\Collection as CollectionData;
use App\Domain\Collections\Actions\CollectionCardSearch;
use App\Domain\Collections\Models\Collection;
use App\Domain\Sets\Actions\SetSearch;
use Illuminate\Http\Request;

class SetCollectionsEditPresenter
{
    private string $cardSearch;

    private Collection $collection;

    private SetSearch $search;

    private ?int $setId;

    private string $setSearch;

    public function __construct(Collection $collection, Request $request)
    {
        $this->collection = $collection;
        $this->setId      = $request->set ?: null;
        $this->setSearch  = $request->setSearch ?: '';
        $this->cardSearch = $request->cardSearch ?: '';
        $this->search     = app(SetSearch::class);
    }

    public function present() : array
    {
        $presentedCollection = new CollectionData([
            'id'            => $this->collection->id,
            'name'          => $this->collection->name,
            'description'   => $this->collection->description,
        ]);

        $sets = $this->getSets();

        return [
            'cards'         => $this->getCards(),
            'collection'    => $presentedCollection,
            'sets'          => $sets,
            'setSearch'     => $this->setSearch,
            'cardSearch'    => $this->cardSearch,
            'setId'         => $this->setId,
            'selectedIndex' => $this->getSelectedIndex($sets),
        ];
    }

    private function getCards() : array
    {
        if (!$this->setId) {
            return [];
        }

        return (new CollectionCardSearch(
            $this->collection,
            $this->cardSearch,
            $this->setId))
            ->execute([
                'exactSet' => true,
            ])
            ->sortBy('collector_number')
            ->values()
            ->toArray();
    }

    private function getSelectedIndex(array $sets) : ?int
    {
        $setId = $this->setId;
        if (!$setId) {
            return null;
        }

        return collect($sets)->search(function ($set) use ($setId) {
            return $set->id == $setId;
        });
    }

    private function getSets() : array
    {
        return $this->search
            ->execute($this->setSearch, ['id', 'code', 'name'])
            ->all();
    }
}
