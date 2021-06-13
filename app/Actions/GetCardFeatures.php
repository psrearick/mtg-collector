<?php


namespace App\Actions;


use App\Domain\Cards\Models\Card;

class GetCardFeatures
{
    private $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    private function getAllFrameEffects() : array
    {
        $frameEffects = [];
        $frameEffectsStrings = [];
        foreach ($this->card->frameEffects as $frameEffect) {
            $frameEffects[] = $frameEffect->name;
            $frameEffectsStrings[] = $frameEffect->name . " frame";
        }
        return [
            'frameEffects'          => $frameEffects,
            'frameEffectsStrings'   => $frameEffectsStrings,
        ];
    }

    public function getFrameEffects() : array
    {
        return $this->getAllFrameEffects()['frameEffects'];
    }

    public function getFrameEffectsString() : string
    {
        return implode(", ", $this->getAllFrameEffects()['frameEffectsStrings']);
    }

    public function getBorderColor() : string
    {
        return $this->card->borderColor ?: "";
    }

    public function getBorderColorString() : string
    {
        $borderColor = $this->getBorderColor();

        if (!$borderColor) {
            return "";
        }

        if ($borderColor == 'black') {
            return "";
        }

        if ($borderColor == 'borderless') {
            return $borderColor;
        }

        return $borderColor . " border";
    }

    public function getAlternateArt() : bool
    {
        return !!$this->card->isAlternate;
    }

    public function getAlternateArtString() : string
    {
        return $this->getAlternateArt() ? "alternate art" : "";
    }

    public function getFullArt() : bool
    {
        return !!$this->card->isFullArt;
    }

    public function getFullArtString() : string
    {
        return $this->getFullArt() ? "full art" : "";
    }

    public function getFoilOnly() : bool
    {
        return $this->card->hasFoil && !$this->card->hasNonFoil;
    }

    public function getFoilOnlyString() : string
    {
        return $this->getFoilOnly() ? "foil" : "";
    }

    public function getPromo() : bool
    {
        return !!$this->card->isPromo;
    }

    public function getPromoString() : string
    {
        return $this->getPromo() ? "promo" : "";
    }

    public function getTextless() : bool
    {
        return !!$this->card->isTextless;
    }

    public function getTextlessString() : string
    {
        return $this->getTextless() ? "textless" : "";
    }

    public function getTimeshifted() : bool
    {
        return !!$this->card->isTimeshifted;
    }

    public function getTimeshiftedString() : string
    {
        return $this->getTimeshifted() ? "timeshifted" : "";
    }

    public function getLayout() : string
    {
        return $this->card->layout ?: "";
    }

    public function getLayoutString() : string
    {
        if (!$this->getLayout()) {
            return "";
        }

        if ($this->getLayout() == 'normal') {
            return "";
        }

        return $this->getLayout() . " layout";
    }
}
