<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;
use App\Domain\Sets\Models\Set;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SetRepository
//class SetRepository extends Repository
{
    public Builder $query;

    public Collection $results;
//    public string $class = "App\Domain\Sets\Models\Set";

    public string $table = 'sets';

    public function __construct()
    {
        $this->query = DB::table($this->table);
    }

//    public function equals(string $field, string $value) : SetRepository
//    {
//        if ($field) {
//            $this->query = $this->query->where('sets.' . $field, '=', $value);
//
//            return $this;
//        }
//        $this->query = $this->query->where('sets.name', '=', $term)
//            ->orWhere('sets.code', '=', $term);
//
//        return $this;
//    }

    public function ids() : array
    {
        if (!isset($this->results)) {
            $this->run();
        }

        return $this->results->pluck('id')->toArray();
    }

    public function like(string $term, string $field = 'name') : SetRepository
    {
        $searchTerm  = '%' . $term . '%';
        $this->query = $this->query->where($this->table . '.' . $field, 'like', $searchTerm)
            ->orWhere('sets.code', 'like', $searchTerm);

        return $this;
    }

//    public function startsWith(string $term, string $field = 'name') : SetRepository
//    {
//        $this->query = $this->query->where('sets.name', 'LIKE', $term . '%')
//            ->orWhere('sets.code', 'LIKE', $term . '%');
//
//        return $this;
//    }

    public function run() : SetRepository
    {
        $this->results = $this->query->get();

        return $this;
    }
}
