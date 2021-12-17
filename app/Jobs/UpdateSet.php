<?php

namespace App\Jobs;

use App\Actions\DownloadFileAWSAction as DownloadFileAction;
use App\Domain\Sets\Models\Set;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DownloadFileAction $downloadFile;

    private array $set;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $set)
    {
        $this->set          = $set;
        $this->downloadFile = new DownloadFileAction();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setSVG($this->getSet());
    }

    private function getSet() : Set
    {
        $set = $this->set;
        echo '    Updating Set: ' . $set['name'] ?? '' . PHP_EOL;

        return Set::firstOrCreate([
            'setId'             => $set['id'] ?? null,
        ], [
            'code'              => $set['code'] ?? null,
            'mtgoCode'          => $set['mtgo_code'] ?? null,
            'tcgPlayerGroupId'  => $set['tcgplayer_id'] ?? null,
            'name'              => $set['name'] ?? null,
            'type'              => $set['set_type'] ?? null,
            'releaseDate'       => $set['released_at'] ?? null,
            'block'             => $set['block'] ?? null,
            'blockCode'         => $set['block_code'] ?? null,
            'parentCode'        => $set['parent_set_code'] ?? null,
            'setSize'           => $set['card_count'] ?? null,
            'printedSetSize'    => $set['printed_size'] ?? null,
            'isOnlineOnly'      => $set['digital'] ?? null,
            'isFoilOnly'        => $set['foil_only'] ?? null,
            'isNonFoilOnly'     => $set['nonfoil_only'] ?? null,
            'scryfallUri'       => $set['scryfall_uri'] ?? null,
            'scryfallApiUri'    => $set['uri'] ?? null,
            'scryfallSvgUri'    => $set['icon_svg_uri'] ?? null,
            'scryfallApiSearch' => $set['search_uri'] ?? null,
        ]);
    }

    private function setSVG(Set $currentSet) : void
    {
        if ($currentSet->svgPath) {
            return;
        }

        $url      = $currentSet->scryfallSvgUri;
        $name     = strtolower($currentSet->code . '_icon');
        $format   = 'svg';
        $path     = 'public/images/setIcons';
        $filepath = "$path/$name.$format";
        $file     = [
            'url'          => $url,
            'format'       => $format,
            'storage_path' => $path,
            'name'         => $name,
        ];

        $this->downloadFile->execute($file);
        $currentSet->svgPath = $filepath;
        $currentSet->save();
    }
}
