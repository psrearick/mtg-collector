<?php

namespace App\App\Client\Presenters;

use App\App\Base\Presenter;
use App\App\Client\Traits\WithLoadAttribute;
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

        $card->printings    = $card->printings();
        $card->printingSets = $card->printingSets();

        $attributes = [
            'scryfall_card' => $card->scryfall_card,
            'price_normal'  => $card->price_normal,
            'price_foil'    => $card->price_foil,
            'image_url'     => $card->image_url,
            'feature'       => $card->feature,
        ];

        foreach ($attributes as $attributeName => $attribute) {
            $card->{$attributeName} = $attribute;
        }

        return $card;
    }
}
