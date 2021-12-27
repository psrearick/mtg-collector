<?php

namespace App\Domain\Cards\Actions;

use App\Domain\Collections\Models\ImportCard;
use App\Domain\Cards\Actions\CardSearch;
use Illuminate\Support\Collection;
use App\Domain\Cards\Actions\GetComputed;

class MatchImportToCard
{
    public function execute(ImportCard $card) : Collection
    {
        $cardSearch = app(CardSearch::class)->execute(null, [
            'perPage' => 0,
            'withImage' => false,
            'exact' => false,
            'set'   => $card->printing,
            'card' => $card->name,
        ]);

        $cards = optional($cardSearch['cards'])->map(function ($card) {
            $computed = new GetComputed($card->load(['set','finishes', 'frameEffects']));
            $computedCard = $computed->add('feature')->get();
            return [
                'card' => $computedCard,
                'otherPrintings' => (GetPrintings::getOtherPrintings($computedCard))->map(function ($printing) {
                    return (new GetComputed($printing->load(['set','finishes', 'frameEffects'])))->add('feature')->get();
                })->filter(function ($printing) {
                    return $printing->set;
                }),
            ];
        });

        return $cards;
    }
}