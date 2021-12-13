<?php

namespace App\Domain\Sets\Actions;

use App\App\Client\Repositories\SetRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SetSearch
{
    public SetRepository $sets;

    public function __construct(SetRepository $setRepository)
    {
        $this->sets = $setRepository;
    }

    // public function paginate(int $perPage = 15) : LengthAwarePaginator
    // {
    //     return $this->sets->getPaginated(['perPage' => $perPage]);
    // }

    public function execute(string $searchTerm = '', array $fields = []) : Collection
    {
        if ($fields) {
            $this->sets->selectMany($fields);
        }

        if ($searchTerm) {
            $this->sets->like($searchTerm);
        }

        return $this->sets->get();
    }
}
