<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;

class SetRepository extends Repository
{
    public string $class = "App\Domain\Sets\Models\Set";

    public string $table = 'sets';

    public function equals(string $field, string $value) : SetRepository
    {
        if ($field) {
            $this->query = $this->query->where('sets.' . $field, '=', $value);

            return $this;
        }
        $this->query = $this->query->where('sets.name', '=', $term)
            ->orWhere('sets.code', '=', $term);

        return $this;
    }

    public function like(string $term, string $field = 'name') : SetRepository
    {
        $searchTerm  = '%' . $term . '%';
        $this->query = $this->query->where('sets.name', 'like', $searchTerm)
            ->orWhere('sets.code', 'like', $searchTerm);

        return $this;
    }

    public function startsWith(string $term, string $field = 'name') : SetRepository
    {
        $this->query = $this->query->where('sets.name', 'LIKE', $term . '%')
            ->orWhere('sets.code', 'LIKE', $term . '%');

        return $this;
    }
}
