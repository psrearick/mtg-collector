<?php

namespace App\App\Client\DataObjects;

use App\App\Contracts\DataObjectInterface;

class Collection implements DataObjectInterface
{
    public string $description;

    public int $id;

    public string $name;

    protected bool $is_public;

    public function __construct(array $data)
    {
        $this->id           = $data['id'] ?: null;
        $this->name         = $data['name'] ?: '';
        $this->description  = $data['description'] ?? '';
        $this->is_public    = $data['is_public'] ?? false;
    }

    public function toArray() : array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'is_public'     => $this->is_public,
        ];
    }
}
