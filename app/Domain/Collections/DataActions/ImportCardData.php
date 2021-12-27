<?php

namespace App\Domain\Collections\DataActions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\App\Client\Imports\CollectionImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;

class ImportCardData
{
    public function execute(Request $request) : Collection
    {
        $collection = $request->get('collection');
        $form = $request->get('form');
        if ($form['type'] === 'csv') {
            $import = Auth::user()->imports()->create([
                'collection_id' => $collection,
            ]);
            $file = optional($request->file())['form']['file'];
            $collection = new CollectionImport;
            Excel::import($collection, $file, null, \Maatwebsite\Excel\Excel::CSV);
            foreach ($collection->data as $card) {
                $card->import()->associate($import->id)->save();
            }
        }
        return $import->importCards;
    }
}