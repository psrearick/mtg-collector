<?php

namespace App\App\Client\Presenters;

use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use App\Domain\Sets\Models\Set;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Str;

class SetCollectionsPresenter
{
    private string $card;

    private Set $set;

    public function __construct(Set $set, string $card)
    {
        $this->set        = $set;
        $this->card       = $card;
    }

    public function getSetCards() : BaseCollection
    {
        return $this->set->cards
            ->load('frameEffects')
            ->map(function (Card $card) {
                $computed = new GetComputed($card);
                $computedCard = $computed
                    ->add('feature')
                    ->get();

                return collect([
                    'id'                 => $computedCard->id,
                    'number'             => $computedCard->collectorNumber,
                    'name'               => $computedCard->name,
                    'features'           => $computedCard->feature,
                ]);
            })->sortBy('number')->values();
    }

    public function present() : BaseCollection
    {
        $result = $this->getSetCards()->sortBy([
            ['number', 'asc'],
            ['foil', 'asc'],
        ])->values();

        if ($cardTerm = $this->card) {
            $result = $result->filter(function ($card) use ($cardTerm) {
                return Str::contains(Str::lower($card->get('name')), Str::lower($cardTerm));
            });
        }

        return $result;
    }
}
