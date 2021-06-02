<?php

namespace App\Jobs;

use App\Actions\DownloadFileAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use pcrov\JsonReader\JsonReader;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class ImportCardData implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Pricing data URL
        $file = [
            'url'           => "https://mtgjson.com/api/v5/AllPrintings.json",
            'format'        => 'json',
            'storage_path'  => 'dumps/printings',
        ];

        $save_file_loc = (new DownloadFileAction())->execute($file, 'Ymd', 5);

//        // open pricing json file and get prices
//        $reader = new JsonReader();
//        $reader->open($save_file_loc);
//        $reader->read();
//        $reader->read('data');
//        $reader->read();
//        // loop through cards
//        while($reader->type() === JsonReader::OBJECT) {
//            $uuid = $reader->name();
//            $normal = 0;
//            $foil = 0;
//            $data = $reader->value();
//
//            $tcg = $this->recursiveFind($data, 'tcgplayer');
//            if (!is_null($tcg)){
//                if (array_key_exists('retail', $tcg)){
//                    $retail = $tcg['retail'];
//                    if (array_key_exists('normal', $retail)){
//                        $normal = end($retail['normal']);
//                    }
//                    if (array_key_exists('foil', $retail)) {
//                        $foil = end($retail['foil']);
//                    }
//                }
//            }
//
//            if ($uuid && $tcg) {
//                // update prices
//                CardPrices::updateOrCreate(
//                    ['uuid' => $uuid],
//                    ['foil' => $foil, 'normal' => $normal]
//                );
//            }
//
//            $reader->next();
//        }
//
//        $reader->close();
//
//        return 0;
    }

    private function recursiveFind(array $haystack, string $needle)
    {
        $iterator  = new RecursiveArrayIterator($haystack);
        $recursive = new RecursiveIteratorIterator(
            $iterator,
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($recursive as $key => $value) {
            if ($key === $needle) {
                return $value;
            }
        }

        return '';
    }
}
