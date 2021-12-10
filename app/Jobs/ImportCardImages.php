<?php

namespace App\Jobs;

use App\Actions\DownloadFileAction;
use App\Domain\Cards\Actions\GetCardImage;
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

    private ?string $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Card $card, ?string $url = '')
    {
        $this->card     = $card;
        $this->url      = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url  = $this->url;
        $card = $this->card;
        if (!$url) {
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

        $card->imagePath          = 'storage/' . $cardPath; // images/cards/FILENAME
        $card->save();
    }
}
