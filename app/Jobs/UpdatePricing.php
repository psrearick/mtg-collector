<?php

namespace App\Jobs;

use App\Domain\Cards\Models\Card;
use App\Domain\Prices\Models\PriceProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdatePricing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ?Card $card;

    private array $cardData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $cardData, ?Card $card)
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
        if (!$prices = $this->cardData['prices']) {
            return;
        }

        $provider = PriceProvider::firstOrCreate(['name' => 'scryfall'])->id;
        $card     = $this->card ?: Card::where('cardId', '=', $this->cardData['id'])->first();

        if (!$card) {
            return;
        }

        foreach ($prices as $type => $price) {
            $card->prices()->updateOrCreate(
                [
                    'card_id'       => $card->id,
                    'provider_id'   => $provider,
                    'foil'          => $type == 'usd_foil' || $type == 'usd_etched',
                    'type'          => $type,
                ],
                [
                    'price'         => $price,
                ]
            );
        }
    }
}
