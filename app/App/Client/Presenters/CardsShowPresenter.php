<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\Traits\WithLoadAttribute;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\DataActions\CardDataAction;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CardsShowPresenter extends Presenter
{
    use WithLoadAttribute;

    protected Card $card;

    protected CardDataAction $cardDataAction;

    public function __construct(Card $card)
    {
        $this->card           = $card;
        $this->cardDataAction = app(CardDataAction::class);
    }

    public function present() : Model
    {
        $card = Card::with([
            'set',
            'colors',
            'keywords',
            'faces',
            'finishes',
            'games',
            'frameEffects',
            'legalities',
            'prices',
            'prices.priceProvider',
            'promoTypes',
            'relatedObjects',
            'collections',
        ])->find($this->card->id);

        $card->printings = $this->cardDataAction->getOtherPrintings($card)
            ->load('frameEffects', 'set', 'prices', 'prices.priceProvider')
            ->map(function ($printing) {
                $computed = new GetComputed($printing);
                $computedCard = $computed
                ->add('feature')
                ->add('allPrices')
                ->get();

                return $computedCard;
            })
            ->sortBy('set.name')
            ->values();

        $computed     = new GetComputed($card);
        $computedCard = $computed
            ->add('feature')
            ->add('allPrices')
            ->add('image_url')
            ->add('set_image_url')
            ->get();

        $attributes = [
            'scryfall_card' => $computedCard->scryfall_card,
            'prices'        => $computedCard->allPrices,
            'image_url'     => $computedCard->image_url,
            'set_image_url' => $computedCard->set_image_url,
            'feature'       => $computedCard->feature,
        ];

        foreach ($attributes as $attributeName => $attribute) {
            $card->{$attributeName} = $attribute;
        }

        $allFinishPrices   = [];
        $card->allFinishes = $card->finishes->pluck('name')->map(function ($finish) use (&$allFinishPrices, $card) {
            $finishRendered = Str::ucfirst($finish);
            $allFinishPrices[$finishRendered] = $card->allPrices[$finish] ?: 0;

            return $finishRendered;
        })->all();

        $card->allFinishPrices = $allFinishPrices;

        return $card;
    }
}
