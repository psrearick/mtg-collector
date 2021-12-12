<?php

namespace App\Jobs;

use App\Actions\DownloadFileAWSAction as DownloadFileAction;
use App\Domain\Cards\Actions\GetCardImage;
use App\Domain\Cards\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCardImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Card $card;

    private ?string $url;

    private string $format;

    private DownloadFileAction $downloadFile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $cardId, ?string $url = '', $format = 'normal')
    {
        $this->card     = Card::find($cardId);
        $this->url      = $url;
        $this->format   = $format;
        $this->downloadFile = new DownloadFileAction;
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
            $attr = match($this->format) {
                'png'           => 'imagePngUri',
                'borderCrop'    => 'imageBorderCropUri',
                'artCrop'       => 'imageArtCropUri',
                'large'         => 'imageLargeUri',
                'normal'        => 'imageNormalUri',
                'small'         => 'imageSmallUri',
                default         => 'imageNormalUri',
            };

            $url = $card->$attr;
        }

        if (!$url) {
            return;
        }

        $basename = basename($url);
        $filename = substr($basename, 0, strpos($basename, '?'));
        $path = 'public/images/cards';
        $filepath =  "$path/$filename"; 
        $exp = explode('.', $filename);
        $format = array_pop($exp);
        $name = implode('.', $exp);

        $file = [
            'url'           => $url,
            'format'        => $format,
            'storage_path'  => $path,
            'name'          => $name,
        ];

        $this->downloadFile->execute($file);
        $card->imagePath = $filepath;
        $card->save();
    }
}
