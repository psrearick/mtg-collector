<?php

namespace App\Jobs;

use App\Domain\CardAttributes\Models\Keyword;
use App\Domain\Cards\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetKeywords implements ShouldQueue
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
        $keywords   = $cardData['keywords'] ?? null;

        if (!$keywords) {
            return;
        }

        foreach ($keywords as $keywordName) {
            $keyword = Keyword::firstOrCreate(
                [
                    'name' => $keywordName,
                ]
            );
            $card->keywords()->syncWithoutDetaching($keyword->id);
        }
    }
}
