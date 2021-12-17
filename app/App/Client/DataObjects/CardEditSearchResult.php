<?php

namespace App\App\Client\DataObjects;

use App\App\Contracts\DataObjectInterface;

class CardEditSearchResult implements DataObjectInterface
{
    public array $collected;

    public string $features;

    public array $finishes;

    public int $id;

    public string $image;

    public string $name;

    public array $quantities;

    public string $set_code;

    public string $set_name;

    public array $today;

    public function __construct(array $data)
    {
        $this->id             = $data['id'] ?? null;
        $this->name           = $data['name'] ?? '';
        $this->set_code       = strtoupper($data['set_code'] ?? '');
        $this->set_name       = $data['set_name'] ?? [];
        $this->collected      = $data['collected'] ?? [];
        $this->features       = $data['features'] ?? '';
        $this->today          = $data['today'] ?? [];
        $this->quantities     = $data['quantities'] ?? [];
        $this->finishes       = $data['finishes'] ?? [];
        $this->image          = $data['image'] ?? '';
    }

    public function toArray() : array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'set_name'       => $this->set_name,
            'set_code'       => $this->set_code,
            'collected'      => $this->collected,
            'features'       => $this->features,
            'today'          => $this->today,
            'quantities'     => $this->quantities,
            'finishes'       => $this->finishes,
            'image'          => $this->image,
        ];
    }
}
