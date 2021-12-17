<?php

namespace App\App\Client\Presenters;

use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use Illuminate\Support\Collection;

class SetCollectionCardsPresenter
{
    private Card $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function present() : Collection
    {
        $compute  = new GetComputed($this->card);
        $computed = $compute
            ->add('feature')
            ->add('allPrices')
            ->add('image_url')
            ->get();

        return collect([
            'id'        => $computed->id,
            'name'      => $computed->name,
            'number'    => $computed->collectorNumber,
            'prices'    => $computed->allPrices,
            'features'  => $computed->feature,
            'finishes'  => $computed->finishes->pluck('name'),
            'image_url' => $computed->image_url,
        ]);
    }
}
