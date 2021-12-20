<?php

namespace App\App\Client\DataObjects;

use App\App\Contracts\DataObjectInterface;

class Collection implements DataObjectInterface
{
    public string $description;

    public int $id;

    public string $name;

    public function __construct(array $data)
    {
        $this->id           = $data['id'] ?: null;
        $this->name         = $data['name'] ?: '';
        $this->description  = $data['description'] ?: '';
    }

    public function toArray() : array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
        ];
    }
}
