<?php

namespace App\App\Client\Repositories;

class SetsRepository extends Repository
{
    public string $class = "App\Domain\Sets\Models\Set";

    public string $table = 'sets';

    public function equals(string $term) : SetsRepository
    {
        $this->query = $this->query->where('sets.name', '=', $term)
            ->orWhere('sets.code', '=', $term);

        return $this;
    }

    public function startsWith(string $term) : SetsRepository
    {
        $this->query = $this->query->where('sets.name', 'LIKE', $term . '%')
            ->orWhere('sets.code', 'LIKE', $term . '%');

        return $this;
    }
}
