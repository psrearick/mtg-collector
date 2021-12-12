<?php

namespace App\Domain\Cards\Actions;

use App\Domain\Cards\Models\Card;
use Illuminate\Support\Str;

class GetComputed
{
    private Card $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function add(string $attribute)
    {
        $method                   = 'get' . Str::studly($attribute);
        $this->card->{$attribute} = $this->{$method}();

        return $this;
    }

    /**
     * @return Card
     */
    public function get()
    {
        return $this->card;
    }

    /**
     * Get strifigied card features
     *
     * @return string
     */
    public function getFeature() : string
    {
        $features    = $this->getFeatures();
        $allFeatures = [
            $features['frameEffectsString'],
            $features['borderColorString'],
            $features['fullArtString'],
            //            $features['alternateArtString'],
            $features['foilOnlyString'],
            $features['promoString'],
            $features['textlessString'],
            //            $features['timeshiftedString'],
            $features['layoutString'],
        ];
        $featureStrings = [];
        foreach ($allFeatures as $feature) {
            if ($feature && strlen($feature) > 0) {
                $featureStrings[] = $feature;
            }
        }

        return implode(', ', $featureStrings);
    }

    /**
     * Get an array of card features
     *
     * @return array
     */
    public function getFeatures() : array
    {
        $featureCollector = new GetCardFeatures($this->card);

        return [
            'frameEffects'        => $featureCollector->getFrameEffects(),
            'frameEffectsString'  => $featureCollector->getFrameEffectsString(),
            'borderColor'         => $featureCollector->getBorderColor(),
            'borderColorString'   => $featureCollector->getBorderColorString(),
            'fullArt'             => $featureCollector->getFullArt(),
            'fullArtString'       => $featureCollector->getFullArtString(),
            //            'alternateArt'        => $featureCollector->getAlternateArt(),
            //            'alternateArtString'  => $featureCollector->getAlternateArtString(),
            'foilOnly'            => $featureCollector->getFoilOnly(),
            'foilOnlyString'      => $featureCollector->getFoilOnlyString(),
            'promo'               => $featureCollector->getPromo(),
            'promoString'         => $featureCollector->getPromoString(),
            'textless'            => $featureCollector->getTextless(),
            'textlessString'      => $featureCollector->getTextlessString(),
            //            'timeshifted'         => $featureCollector->getTimeshifted(),
            //            'timeshiftedString'   => $featureCollector->getTimeshiftedString(),
            'layout'              => $featureCollector->getLayout(),
            'layoutString'        => $featureCollector->getLayoutString(),
        ];
    }

    /**
     * Get image url, if there isn't one, get it from scryfall and save it
     *
     * @param bool $dispatch
     * @return string
     */
    public function getImageUrl(bool $dispatch = true) : string
    {
        return asset($this->card->imagePath);
    }

    /**
     * get card foil price
     *
     * @return float|null
     */
    public function getPriceFoil() : ?float
    {
        return $this->getPrice(true);
    }

    /**
     * Get card non foil price
     *
     * @return float|null
     */
    public function getPriceNormal() : ?float
    {
        return $this->getPrice();
    }

    /**
     * Get card price, default to non foil
     *
     * @param bool $foil
     * @return mixed|null
     */
    private function getPrice(bool $foil = false) : ?float
    {
        return optional(
            $this->card->prices
                ->where('priceProvider.name', '=', 'tcgplayer')
                ->where('foil', $foil)
                ->first()
        )->price ?: optional(
            $this->card->prices
                ->where('priceProvider.name', '=', 'scryfall')
                ->where('foil', $foil)
                ->first()
        )->price;
    }
}
