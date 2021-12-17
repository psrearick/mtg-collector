<?php

namespace App\Jobs;

use App\Domain\CardAttributes\Models\Game;
use App\Domain\Cards\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetGames implements ShouldQueue
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
        $games      = $cardData['games'] ?? null;

        if (!$games) {
            return;
        }

        foreach ($games as $gameName) {
            $game = Game::firstOrCreate(
                [
                    'name' => $gameName,
                ]
            );
            $card->games()->syncWithoutDetaching($game->id);
        }
    }
}
