<?php

namespace App\App\Client\DataObjects;

class CardSearchResult
{
    public int $id;
    public string $name;
    public string $set;
    public bool $foil;
    public string $foil_formatted;
    public string $features;
    public float $today;
    public string $acquired_date;
    public float $acquired_price;
    public int $quantity;

    public function __construct(array $result)
    {
        $this->id = $result['id'] ?? null;
        $this->name = $result['name'] ?? '';
        $this->set = $result['set'] ?? '';
        $this->foil = $result['foil'] ?? false;
        $this->foil_formatted = $result['foil_formatted'] ?? '';
        $this->features = $result['features'] ?? '';
        $this->today = $result['today'] ?? 0.0;
        $this->acquired_date = $result['aquired_dated'] ?? '';
        $this->acquired_price = $result['acquired_price'] ?? 0.0;
        $this->quantity = $result['quantity'] ?? 0;
    }

    public function toArray()
    {
        return [
                'id'             => $this->id,
                'name'           => $this->name,
                'set'            => $this->set,
                'foil'           => $this->foil,
                'foil_formatted' => $this->foil_formatted,
                'features'       => $this->features,
                'today'          => $this->today,
                'acquired_date'  => $this->acquired_date,
                'acquired_price' => $this->acquired_price,
                'quantity'       => $this->quantity,
        ];
    }
}