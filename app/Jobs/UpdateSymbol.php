<?php

namespace App\Jobs;

use App\Actions\DownloadFileAWSAction as DownloadFileAction;
use App\Domain\Symbols\Models\Symbol;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateSymbol implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DownloadFileAction $downloadFile;

    private array $symbolData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $symbolData)
    {
        $this->symbolData   = $symbolData;
        $this->downloadFile = new DownloadFileAction();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->setSVG($this->getSymbol());
    }

    private function getSymbol() : Symbol
    {
        $symbolData = $this->symbolData;
        echo '    Updating Symbol: ' . $symbolData['symbol'] ?? '' . PHP_EOL;

        return Symbol::firstOrCreate([
            'symbol'        => $symbolData['symbol'],
        ], [
            'svgUri'                    => $symbolData['svg_uri'] ?? null,
            'looseVariant'              => $symbolData['loose_variant'] ?? null,
            'english'                   => $symbolData['english'] ?? null,
            'transpose'                 => $symbolData['transpose'] ?? null,
            'representsMana'            => $symbolData['represents_mana'] ?? null,
            'appearsInManaCosts'        => $symbolData['appears_in_mana_costs'] ?? null,
            'cmc'                       => $symbolData['cmc'] ?? null,
            'funny'                     => $symbolData['funny'] ?? null,
        ]);
    }

    private function setSVG(Symbol $symbol) : void
    {
        if (!$symbol->svgPath) {
            $url      = $symbol->svgUri;
            $name     = strtolower($symbol->id . '_symbol');
            $format   = 'svg';
            $path     = 'public/images/symbols';
            $filepath = "$path/$name.$format";
            $file     = [
                'url'          => $url,
                'format'       => $format,
                'storage_path' => $path,
                'name'         => $name,
            ];

            $this->downloadFile->execute($file);
            $symbol->svgPath = $filepath;
            $symbol->save();
        }
    }
}
