<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\Repositories\CollectionRepository;
use App\App\Client\Repositories\SetRepository;
use App\Domain\Collections\Models\Collection;
use App\Domain\Shared\Models\SharedCollection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Collection as SupportCollection;

class SharedCollectionsIndexPresenter extends Presenter
{
    protected array $request;

    protected EloquentCollection $shared;

    public function __construct(array $request)
    {
        $this->request  = $request;
        $this->shared   = SharedCollection::with(['collection', 'collection.user'])->get()->where('collection.is_public', true)->sortBy('name');
    }

    public function present() : SupportCollection
    {
        return $this->shared->map(function ($share) {
            return collect([
                'id'            => $share->id,
                'collection_id' => $share->collection->id,
                'name'          => $share->collection->name,
                'description'   => $share->collection->description,
                'user_name'     => $share->collection->user->name,
                'user_id'       => $share->collection->user->id,
            ]);
        });
    }
}
