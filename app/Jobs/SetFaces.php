<?php

namespace App\Jobs;

use App\Domain\Cards\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetFaces implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Card $card;

    private array $cardData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $cardData, Card $card)
    {
        $this->cardData = $cardData;
        $this->card     = $card;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $cardData   = $this->cardData;
        $card       = $this->card;
        $cardFaces  = $cardData['card_faces'] ?? null;

        if (!$cardFaces) {
            return;
        }

        foreach ($cardFaces as $cardFace) {
            $card->faces()->updateOrCreate([
                'printedName'        => $cardFace['printed_name'] ?? null,
                'printedText'        => $cardFace['printed_text'] ?? null,
                'printedTypeLine'    => $cardFace['printed_type_line'] ?? null,
            ], [
                'name'               => $cardFace['name'] ?? null,
                'artist'             => $cardFace['artist'] ?? null,
                'flavorText'         => $cardFace['flavor_text'] ?? null,
                'illustrationId'     => $cardFace['illustration_id'] ?? null,
                'loyalty'            => $cardFace['loyalty'] ?? null,
                'manaCost'           => $cardFace['mana_cost'] ?? null,
                'oracleText'         => $cardFace['oracle_text'] ?? null,
                'power'              => $cardFace['power'] ?? null,
                'toughness'          => $cardFace['toughness'] ?? null,
                'typeLine'           => $cardFace['type_line'] ?? null,
                'watermark'          => $cardFace['watermark'] ?? null,
            ]);
        }
    }
}
