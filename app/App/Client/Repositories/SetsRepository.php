<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;

class SetsRepository extends Repository
{
    public string $class = "App\Domain\Sets\Models\Set";

    public string $table = 'sets';

    public function equals(string $term, string $field = '') : SetsRepository
    {
        if ($field) {
            $this->query = $this->query->where('sets.' . $field, '=', $term);

            return $this;
        }
        $this->query = $this->query->where('sets.name', '=', $term)
            ->orWhere('sets.code', '=', $term);

        return $this;
    }

    public function like(string $term, string $field = 'name') : SetsRepository
    {
        $searchTerm  = '%' . $term . '%';
        $this->query = $this->query->where('sets.name', 'like', $searchTerm)
            ->orWhere('sets.code', 'like', $searchTerm);

        return $this;
    }

    public function startsWith(string $term) : SetsRepository
    {
        $this->query = $this->query->where('sets.name', 'LIKE', $term . '%')
            ->orWhere('sets.code', 'LIKE', $term . '%');

        return $this;
    }
}
