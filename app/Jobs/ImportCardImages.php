<?php

namespace App\Jobs;

use App\Actions\DownloadFileAction;
use App\Domain\Cards\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ImportCardImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Card $card;

    private string $imageUrl;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Card $card, string $imageUrl)
    {
        $this->card     = $card;
        $this->imageUrl = $imageUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (!$url = $this->card->image_url) {
            return;
        }

        $basename = basename($url);
        $filename = substr($basename, 0, strpos($basename, '?'));

        $publicDir  = 'images/cards';
        $cardPath   = $publicDir . '/' . $filename; // images/cards/FILENAME
        $storageDir = 'public/' . $publicDir; // public/images/cards
        Storage::makeDirectory($storageDir);
        $appDir      = 'app/public';
        $appPath     = $appDir . '/' . $cardPath; // app/public/images/cards/FILENAME
        $storagePath = storage_path($appPath);

        app(DownloadFileAction::class)->saveFile($storagePath, $url);

        $card                     = Card::find($this->card->id);
        $card->imagePath          = $cardPath; // images/cards/FILENAME
        $card->scryfall_image_url = $url;
        $card->save();
    }
}
