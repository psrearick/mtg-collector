<?php

namespace App\Jobs;

use App\Domain\CardAttributes\Models\FrameEffect;
use App\Domain\Cards\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetFrameEffects implements ShouldQueue
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
        $effects    = $cardData['frame_effects'] ?? null;

        if (!$effects) {
            return;
        }

        foreach ($effects as $effect) {
            $frameEffect = FrameEffect::firstOrCreate(
                [
                    'name' => $effect,
                ]
            );
            $card->frameEffects()->syncWithoutDetaching($frameEffect->id);
        }
    }
}
