<?php

namespace App\App\Client\Repositories;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Repository
{
    public string $class = 'App\Domain\Base\Models\Model';

    public object $query;

    public ?Request $request = null;

    public ?Collection $results = null;

    public string $table = '';

    public function __construct(string $class = null)
    {
        $this->class = $class ?: $this->class;
        $this->query = new $this->class();
    }

    public function equals(string $term) : Repository
    {
        $this->query = $this->query->where($this->table . '.name', '=', $term);

        return $this;
    }

    public function fromRequest(Request $request, string $key, bool $startsWith = true) : Repository
    {
        $this->request = $request;

        if (!$val = $request->get($key)) {
            return $this;
        }

        return $startsWith ? $this->startsWith($val) : $this->equals($val);
    }

    public function get() : Collection
    {
        if (!$this->results) {
            $this->run();
        }

        return $this->results;
    }

    public function getIds() : array
    {
        return $this->get()->pluck('id')->toArray();
    }

    public function getPaginated(int $perPage, int $page = null, Request $request = null) : LengthAwarePaginator
    {
        $request = $request ?: $this->request;
        $page    = $page ?: optional($request)->page;
        if (!$this->results) {
            $this->run();
        }

        return $this->results->paginate($perPage, $this->results->count(), $page)->withQueryString();
    }

    public function run() : Repository
    {
        $this->results = $this->query->get();

        return $this;
    }

    public function select(string $selection) : Repository
    {
        $this->query = $this->query->select($selection);

        return $this;
    }

    public function startsWith(string $term) : Repository
    {
        $this->query = $this->query->where($this->table . '.name', 'LIKE', $term . '%');

        return $this;
    }

    public function toArray() : array
    {
        if (!$this->results) {
            $this->run();
        }

        return $this->results->toArray();
    }

    public function with(array $relations) : Repository
    {
        $this->query = $this->query->with($relations);

        return $this;
    }
}
