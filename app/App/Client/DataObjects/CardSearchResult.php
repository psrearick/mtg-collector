<?php

namespace App\App\Client\DataObjects;

use App\App\Contracts\DataObjectInterface;

class CardSearchResult implements DataObjectInterface
{
    public string $acquired_date;

    public float $acquired_price;

    public string $collector_number;

    public string $features;

    public string $finish;

    public int $id;

    public string $image;

    public string $name;

    public int $quantity;

    public string $set;

    public float $today;

    public function __construct(array $data)
    {
        $this->id               = $data['id'] ?? null;
        $this->name             = $data['name'] ?? '';
        $this->set              = strtoupper($data['set'] ?? '');
        $this->features         = $data['features'] ?? '';
        $this->today            = $data['today'] ?? 0.0;
        $this->acquired_date    = $data['acquired_date'] ?? '';
        $this->acquired_price   = $data['acquired_price'] ?? 0.0;
        $this->quantity         = $data['quantity'] ?? 0;
        $this->finish           = $data['finish'] ?? 'nonfoil';
        $this->image            = $data['image'] ?? '';
        $this->collector_number = $data['collection_number'] ?? '';
    }

    public function toArray() : array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'set'              => $this->set,
            'features'         => $this->features,
            'today'            => $this->today,
            'acquired_date'    => $this->acquired_date,
            'acquired_price'   => $this->acquired_price,
            'quantity'         => $this->quantity,
            'finish'           => $this->finish,
            'image'            => $this->image,
            'collector_number' => $this->collector_number,
        ];
    }
}
