<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;
use App\Domain\Collections\Models\Collection;
use App\Domain\Collections\Models\Folder;

class CollectionFolderRepository extends Repository
{
    public function moveCollection(array $request) : void
    {
        $collection = Collection::find($request['id']);
        if (($folder = $request['destination']) && $request['destination'] > 0) {
            $collection->folder()->associate($folder);
            $collection->save();

            return;
        }
        $collection->folder()->dissociate();
        $collection->save();
    }

    public function moveFolder(array $request) : void
    {
        $folder = Folder::find($request['id']);
        if ($request['destination'] == 0) {
            $folder->saveAsRoot();

            return;
        }

        $destination = Folder::find($request['destination']);
        $folder->appendToNode($destination)->save();
    }

    public function moveItem(array $request) : void
    {
        if ($request['type'] == 'collection') {
            $this->moveCollection($request);

            return;
        }

        $this->moveFolder($request);
    }
}
