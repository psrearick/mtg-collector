<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\Traits\WithLoadAttribute;
use App\Domain\Cards\Actions\GetComputed;
use App\Domain\Cards\Models\Card;
use Illuminate\Database\Eloquent\Model;

class CardsShowPresenter extends Presenter
{
    use WithLoadAttribute;

    protected Card $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function present() : Model
    {
        $card = Card::with([
            'set',
            'colors',
            'keywords',
            'subtypes',
            'supertypes',
            'types',
            'faces',
            'frameEffects',
            'leadershipSkills',
            //            'legalities',
            //            'rulings',
            'tokens',
            'variations',
            'prices',
            'prices.priceProvider',
            'collections',
        ])->find($this->card->id);

        $card->printings = $card->printings()->load('frameEffects')->map(function ($printing) {
            $computed = new GetComputed($printing);
            $computedCard = $computed
                ->add('feature')
                ->add('price_normal')
                ->add('price_foil')
                ->get();

            return $computedCard;
        });

        $card->printingSets = $card->printingSets();

        $computed     = new GetComputed($card);
        $computedCard = $computed
            ->add('feature')
            ->add('priceNormal')
            ->add('priceFoil')
            ->add('image_url')
            ->add('scryfall_card')
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
