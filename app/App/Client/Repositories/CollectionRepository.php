<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class CollectionRepository extends Repository
{
    public function deleteCollection(Collection $collection) : void
    {
        $collection->update([
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function updateCollection(Collection $collection, array $data) : void
    {
        $collection->update([
            'name'          => $data['name'],
            'description'   => $data['description'],
        ]);
    }
}
