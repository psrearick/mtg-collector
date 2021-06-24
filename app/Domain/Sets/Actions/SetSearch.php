<?php

namespace App\Domain\Sets\Actions;

use App\App\Client\Repositories\SetsRepository;
use Illuminate\Database\Eloquent\Collection;

class SetSearch
{
    public static function search(string $searchTerm = '', int $perPage = 15, $fields = '') : Collection
    {
        $sets   = app(SetsRepository::class);

        if ($fields) {
            if (is_array($fields)) {
                $sets->selectMany($fields);
            } else {
                $sets->select($fields);
            }
        }

        if ($searchTerm) {
            $sets->like($searchTerm);
        }

        if ($perPage > 0) {
            return $sets->getPaginated($perPage);
        }

        return $sets->get();
    }
}
