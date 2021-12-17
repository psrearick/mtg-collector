<?php

namespace App\Jobs;

use App\Domain\Cards\Models\Card;
use App\Domain\Sets\Models\Set;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetCardSet implements ShouldQueue
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
        $code       = $cardData['set'] ?? null;

        if (!$code) {
            return;
        }
        $set = Set::firstOrCreate(
            [
                'setId' => $cardData['set_id'],
            ],
            [
                'code'  => $code,
                'name'  => $cardData['set_name'],
                'type'  => $cardData['set_type'],
            ]
        );
        $card->set()->associate($set);
        $card->save();
    }
}
