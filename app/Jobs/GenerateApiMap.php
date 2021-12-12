<?php

namespace App\Jobs;

use App\Actions\DownloadFileAWSAction as DownloadFileAction;
use App\Domain\Cards\Models\Card;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use pcrov\JsonReader\InputStream\IOException;
use pcrov\JsonReader\InvalidArgumentException;
use pcrov\JsonReader\JsonReader;

class GenerateApiMap implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Create and Update API Map
     *
     * Download the dump file
     * Open the file on parse data
     * Loops through sets
     * Loops through cards
     * Saves mapping
     *
     * @return void
     * @throws \pcrov\JsonReader\Exception
     */
    public function handle()
    {
        // Download the dump file
        $file = [
            'url'           => 'https://mtgjson.com/api/v5/AllPrintings.json',
            'format'        => 'json',
            'storage_path'  => 'dumps/printings',
        ];

        $save_file_loc = (new DownloadFileAction())->execute($file, 'Ymd', 5);

        // open json file and get data
        $reader = new JsonReader();

        try {
            $reader->open($save_file_loc);
        } catch (IOException | InvalidArgumentException $e) {
        }
        $reader->read();
        $reader->read('data');
        $reader->read();

        // Loop through sets
        while ($reader->type() === JsonReader::OBJECT) {
            $setData = $reader->value();
            echo 'set: ' . $setData['name'] . PHP_EOL;

            foreach ($setData['cards'] as $cardData) {
                // Get card from scryfall uuid
                // card -> cardId == cardData -> identifiers -> scryfallId
                if (!$cardData['identifiers'] || !$scryfallId = $cardData['identifiers']['scryfallId']) {
                    $reader->next();

                    continue;
                }
                $card = Card::where('cardId', '=', $scryfallId)->first();
                if (!$card) {
                    $reader->next();

                    continue;
                }
                echo '........set: ' . $setData['name'] . ' -- card: ' . $card->name . PHP_EOL;

                // make mapping for scryfall
                // uuid : card -> cardId
                $card->apiMappings()->updateOrCreate(
                    [
                        'identifier' => $scryfallId,
                        'source'     => 'scryfall',
                    ]
                );

                // make mapping for mtg json
                // uuid : cardData -> uuid
                $card->apiMappings()->updateOrCreate(
                    [
                        'identifier' => $cardData['uuid'],
                        'source'     => 'mtgjson',
                    ]
                );
            }

            $reader->next();
        }

        $reader->close();

        echo 'Completed' . PHP_EOL;
    }
}
