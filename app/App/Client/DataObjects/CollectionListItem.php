<?php

namespace App\App\Client\DataObjects;

use App\App\Contracts\DataObjectInterface;

class CollectionListItem implements DataObjectInterface
{
    protected float $acquired;

    protected int $count;

    protected string $description;

    protected int $id;

    protected string $name;

    protected ?CollectionListItem $parent;

    protected string $type;

    protected float $value;

    public function __construct(array $data)
    {
        $this->id          = $data['id'];
        $this->name        = $data['name'];
        $this->description = $data['description'] ?? '';
        $this->type        = $data['type'] ?? 'collection';
        $this->value       = $data['value'] ?? 0.0;
        $this->acquired    = $data['acquired'] ?? 0.0;
        $this->count       = $data['count'] ?? 0;
        $this->parent      = $data['parent'] ?? null;
    }

    public function toArray() : array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'type'          => $this->type,
            'value'         => $this->value,
            'acquired'      => $this->acquired,
            'count'         => $this->count,
            'parent'        => $this->parent,
        ];
    }
}
