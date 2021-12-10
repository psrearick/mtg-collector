<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\Traits\WithLoadAttribute;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Actions\GetPrintings;
use App\Domain\Cards\DataActions\CardDataAction;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Model;

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
            ->load('frameEffects', 'set')
            ->map(function ($printing) {
                $computed = new GetComputed($printing);
                $computedCard = $computed
                ->add('feature')
                ->add('price_normal')
                ->add('price_foil')
                ->get();

                return $computedCard;
            });

        $computed     = new GetComputed($card);
        $computedCard = $computed
            ->add('feature')
            ->add('priceNormal')
            ->add('priceFoil')
            ->add('image_url')
            ->get();

        $attributes = [
            'scryfall_card' => $computedCard->scryfall_card,
            'price_normal'  => $computedCard->priceNormal,
            'price_foil'    => $computedCard->priceFoil,
            'image_url'     => $computedCard->image_url,
            'feature'       => $computedCard->feature,
        ];

        foreach ($attributes as $attributeName => $attribute) {
            $card->{$attributeName} = $attribute;
        }

        return $card;
    }
}
