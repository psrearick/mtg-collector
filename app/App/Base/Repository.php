<?php

namespace App\App\Base;

use App\Domain\Base\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class Repository
{
    public string $class = '\App\Domain\Base\Model';

    public Model $model;

    public Builder $query;

    public Request $request;

    public Collection $results;

    public string $table;

    public function __construct(Model $model)
    {
        $this->class = get_class($model);
        $this->model = $model;
        $this->query = $model->newQuery();
        $this->table = $this->table ?? $this->model->getTable();
    }

    public function equals(string $field, string $value) : Repository
    {
        $this->query = $this->query->where($this->getField($field), '=', $value);

        return $this;
    }

    public function get() : Collection
    {
        if (!isset($this->results)) {
            $this->run();
        }

        return $this->results;
    }

    public function getField(string $field) : string
    {
        return $this->table ? "$this->table.$field" : $field;
    }

//    public function getPaginated(array $pagination) : LengthAwarePaginator
//    {
//        $perPage = $pagination['perPage'] ?: 15;
//        $request = $pagination['request'] ?: $this->request;
//        $page    = $pagination['page '] ?: optional($request)->page;
//
//        if (!$this->results) {
//            $this->run();
//        }
//
//        return $this->results->paginate($perPage, $this->results->count(), $page)->withQueryString();
//    }
//
//    public function ids() : array
//    {
//        if (!$this->results) {
//            $this->run();
//        }
//
//        return $this->results->pluck('id')->toArray();
//    }
//
//    public function in(string $field, array $values) : Repository
//    {
//        $this->query = $this->query->whereIn($this->getField($field), $values);
//
//        return $this;
//    }

    public function like(string $term, string $field = 'name') : Repository
    {
        $searchTerm  = '%' . $term . '%';
        $this->query = $this->query
            ->where($this->getField($field), 'like', $searchTerm);

        return $this;
    }

//    public function loadAttribute(array $attributes) : Repository
//    {
//        if (!$this->results) {
//            $this->run();
//        }
//
//        foreach ($attributes as $attribute) {
//            $key   = $attribute;
//            $value = $attribute;
//
//            if (is_array($attribute)) {
//                $key   = $attribute['key'];
//                $value = $attribute['value'];
//            }
//
//            foreach ($this->results as $item) {
//                $item->{$key} = $item->{$value};
//            }
//        }
//
//        return $this;
//    }
//
//    public function make(string $class) : Repository
//    {
//        $this->class = $class;
//        $this->model = new $class();
//        $this->query = $this->model->newQuery();
//
//        return $this;
//    }

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

    public function selectMany(array $selection) : Repository
    {
        $this->query = $this->query->select($selection);

        return $this;
    }

//    public function startsWith(string $field, string $term) : Repository
//    {
//        $this->query = $this->query->where($this->getField($field), 'LIKE', $term . '%');
//
//        return $this;
//    }
//
//    public function toArray() : array
//    {
//        if (!$this->results) {
//            $this->run();
//        }
//
//        return $this->results->toArray();
//    }

    public function with(array $relations) : Repository
    {
        $this->query = $this->query->with($relations);

        return $this;
    }
}
