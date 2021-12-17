<?php

namespace App\Jobs;

use App\Actions\DownloadFileAWSAction as DownloadFileAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use pcrov\JsonReader\Exception;
use pcrov\JsonReader\InputStream\IOException;
use pcrov\JsonReader\InvalidArgumentException;
use pcrov\JsonReader\JsonReader;

class ImportScryfallData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DownloadFileAction $downloadFile;

    private array $options = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->downloadFile = new DownloadFileAction;
        $this->options      = [
            'sets'      => $options['sets'] ?? true,
            'cards'     => $options['cards'] ?? true,
            'prices'    => $options['prices'] ?? true,
            'symbols'   => $options['symbols'] ?? true,
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() : void
    {
        if ($this->options['sets']) {
            echo 'Updating Sets' . PHP_EOL;
            $this->updateSets();
        }

        if ($this->options['prices'] || $this->options['cards']) {
            echo 'Fetching Card Data' . PHP_EOL;
            $save_file_loc = $this->getFile();

            echo 'Processing Card Data' . PHP_EOL;

            try {
                $this->processCardData($save_file_loc);
            } catch (InvalidArgumentException | Exception $e) {
            }
        }

        if ($this->options['symbols']) {
            echo 'Saving Symbols' . PHP_EOL;
            $this->updateSymbols();
        }

        echo 'Completed' . PHP_EOL;
    }

    /**
     * Download bulk data and return file name
     *
     * @return string
     */
    private function getFile() : string
    {
        $bulkDataDefinition = Http::get('https://api.scryfall.com/bulk-data/default-cards')->json();
        $downloadUri        = $bulkDataDefinition['download_uri'];

        $file = [
            'url'           => $downloadUri,
            'format'        => 'json',
            'storage_path'  => 'dumps/scryfall',
            'name'          => 'default_cards',
        ];

        return $this->downloadFile->execute($file, 'Ymd', 5);
    }

    /**
     * @param string $fileName
     * @throws Exception
     * @throws InvalidArgumentException
     */
    private function processCardData(string $fileName) : void
    {
        $reader = new JsonReader();

        try {
            $reader->open($fileName);
        } catch (IOException | \InvalidArgumentException $e) {
        }

        $reader->read();
        $reader->read();

        while ($reader->type() === JsonReader::OBJECT) {
            $cardData = $reader->value();

            if ($cardData['object'] == 'card') {
                UpdateCard::dispatch($cardData, $this->options)->onQueue('long-running-queue');
            }

            $reader->next();
        }

        $reader->close();
    }

    /**
     * Fetch all sets and add new ones
     */
    private function updateSets() : void
    {
        $sets = Http::get('https://api.scryfall.com/sets')->json()['data'];
        foreach ($sets as $set) {
            UpdateSet::dispatch($set)->onQueue('long-running-queue');
        }
    }

    /**
     * Fetch all symbols and add new ones
     */
    private function updateSymbols() : void
    {
        $symbols = Http::get('https://api.scryfall.com/symbology')->json()['data'];
        foreach ($symbols as $symbol) {
            UpdateSymbol::dispatch($symbol)->onQueue('long-running-queue');
        }
    }
}
