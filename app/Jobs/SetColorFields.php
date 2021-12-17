<?php

namespace App\Jobs;

use App\Domain\CardAttributes\Models\Color;
use App\Domain\Cards\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetColorFields implements ShouldQueue
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

        $types = ['color_identity', 'color_indicator', 'colors', 'produced_mana'];
        foreach ($types as $type) {
            $dataType = $cardData[$type] ?? null;
            if (!$dataType) {
                continue;
            }

            foreach ($dataType as $colorValue) {
                $color = Color::firstOrCreate(
                    [
                        'name' => $colorValue,
                    ]
                );
                $card->colors()->syncWithoutDetaching($color->id, ['type' => $type]);
            }
        }
    }
}
