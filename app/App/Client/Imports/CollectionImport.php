<?php

namespace App\App\Client\Imports;

use App\Domain\Collections\Models\ImportCard;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CollectionImport implements WithHeadingRow, ToCollection
{
    public array $data = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $this->data[] = ImportCard::create([
                'name'              => $row['name'] ?? $row['Name'] ?? $row['card'] ?? $row['Card'] ?? null,
                'quantity'          => $row['quantity'] ?? $row['Quantity'] ?? $row['qty'] ?? $row['Qty'] ?? 1,
                'printing'          => $row['printing'] ?? $row['Printing'] ?? $row['set'] ?? $row['Set'] ?? null,
                'collection_number' => $row['collection_number'] ?? null,
                'finish'            => $row['finish'] ?? $row['Finish'] ?? null,
                'foil'              => $row['foil'] ?? $row['Foil'] ?? null,
                'multiverse_id'     => $row['multiverse_id'] ?? null,
                'scryfall_id'       => $row['scryfall_id'] ?? null,
                'condition'         => $row['condition'] ?? null,
                'language'          => $row['language'] ?? null,
            ]);
        }
    }


    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new ImportCard([
            'name'              => $row['name'] ?? $row['Name'] ?? $row['card'] ?? $row['Card'] ?? null,
            'quantity'          => $row['quantity'] ?? $row['Quantity'] ?? $row['qty'] ?? $row['Qty'] ?? 1,
            'printing'          => $row['printing'] ?? $row['Printing'] ?? $row['set'] ?? $row['Set'] ?? null,
            'collection_number' => $row['collection_number'] ?? null,
            'finish'            => $row['finish'] ?? $row['Finish'] ?? null,
            'foil'              => $row['foil'] ?? $row['Foil'] ?? null,
            'multiverse_id'     => $row['multiverse_id'] ?? null,
            'scryfall_id'       => $row['scryfall_id'] ?? null,
            'condition'         => $row['condition'] ?? null,
            'language'          => $row['language'] ?? null,
        ]);
    }
}