<?php

namespace App\App\Client\Repositories;

use App\App\Base\Repository;
use App\Domain\Collections\Models\Collection;
use Carbon\Carbon;
use Illuminate\Support\Collection as SupportCollection;

class CollectionRepository extends Repository
{
    public function deleteCollection(Collection $collection) : void
    {
        $collection->update([
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function getSummary(SupportCollection $collection) : array
    {
        $count    = 0;
        $current  = 0;
        $acquired = 0;

        $collection->each(function ($card) use (&$count, &$current, &$acquired) {
            $count += $card->quantity;
            $current += $card->quantity * $card->price;
            $acquired += $card->quantity * $card->acquired_price;
        });

        $gainLoss        = $current - $acquired;
        $gainLossPercent = $gainLoss == 0 ? 0 : 1;
        $gainLossPercent = $acquired != 0 ? $gainLoss / $acquired : $gainLossPercent;

        return [
            'totalCards'      => $count,
            'currentValue'    => $current,
            'acquiredValue'   => $acquired,
            'gainLoss'        => $gainLoss,
            'gainLossPercent' => $gainLossPercent,
            'top_five'        => $collection->sortByDesc('price')->take(5)->values(),
        ];
    }

    public function updateCollection(Collection $collection, array $data) : void
    {
        $collection->update([
            'name'          => $data['name'],
            'description'   => $data['description'],
        ]);
    }
}
