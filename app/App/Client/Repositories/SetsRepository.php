<?php


namespace App\App\Client\Repositories;


use App\Domain\Sets\Models\Set;
use Illuminate\Support\Collection;

class SetsRepository extends Repository
{
    public function searchSetsStartingWith(string $term) : Collection
    {
        return Set::where('name','LIKE', $term . "%")
            ->orWhere('code','LIKE', $term . "%")
            ->get();
    }
}
