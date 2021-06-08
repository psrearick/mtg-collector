<?php

namespace App\Jobs;

use App\Actions\DownloadFileAction;
use App\Domain\Cards\Models\Card;
use App\Domain\Prices\Models\PriceProvider;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use pcrov\JsonReader\Exception;
use pcrov\JsonReader\InputStream\IOException;
use pcrov\JsonReader\InvalidArgumentException;
use pcrov\JsonReader\JsonReader;

class ImportCardPrices implements ShouldQueue
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
     * @throws Exception
     */
    public function handle() : void
    {
        // Pricing data URL
        $file = [
            'url'           => 'https://mtgjson.com/api/v5/AllPrices.json',
            'format'        => 'json',
            'storage_path'  => 'dumps/pricing',
        ];

        // Get file
        echo 'Downloading Pricing Data' . PHP_EOL;
        $save_file_loc = (new DownloadFileAction())->execute($file, 'Ymd', 5);

        // Read data
        $reader = new JsonReader();

        try {
            $reader->open($save_file_loc);
        } catch (IOException | InvalidArgumentException $e) {
        }

        $reader->read();

        // Find data object
        $reader->read('data');
        $reader->read();

        // Loop through cards
        while ($reader->type() === JsonReader::OBJECT) {
            $uuid      = $reader->name();
            $data      = $reader->value();

//            echo $uuid . PHP_EOL;

            echo 'Saving Pricing For: ' . $uuid . PHP_EOL;
            $card      = Card::firstOrCreate(['uuid' => $uuid]);
            $providers = $this->ifKey($data, 'paper');

            if (!$providers) {
                $reader->next();

                continue;
            }

            foreach ($providers as $provider => $providerData) {
                $priceProvider = PriceProvider::firstOrCreate(['name' => $provider]);
                $prices        = $this->ifKey($providerData, 'retail');

                if (!$prices) {
                    continue;
                }

                $nonfoilPrices = $this->ifKey($prices, 'normal') ?: [];
                $foilPrices    = $this->ifKey($prices, 'foil') ?: [];
                $nonfoilPrice  = $this->getLatestPrice($nonfoilPrices);
                $foilPrice     = $this->getLatestPrice($foilPrices);

                $card->prices()->updateOrCreate(
                    [
                        'provider_id' => $priceProvider->id,
                        'foil'        => false,
                    ],
                    ['price' => $nonfoilPrice]
                );

                $card->prices()->updateOrCreate(
                    [
                        'provider_id' => $priceProvider->id,
                        'foil'        => true,
                    ],
                    ['price' => $foilPrice]
                );
            }

            $reader->next();
        }

        echo 'Complete' . PHP_EOL;
    }

    private function getLatestPrice(array $prices) : float
    {
        $mostRecentDate = 0;
        $key            = null;
        foreach (array_keys($prices) as $date) {
            $curDate = strtotime($date);
            if ($curDate > $mostRecentDate) {
                $mostRecentDate = $curDate;
                $key            = $date;
            }
        }

        return $key ? $prices[$key] : 0.0;
    }

    /**
     * if key exists in array, return its value, otherwise return null
     *
     * @param array $haystack
     * @param string $needle
     * @return mixed|null
     */
    private function ifKey(array $haystack, string $needle)
    {
        if (!array_key_exists($needle, $haystack)) {
            return null;
        }

        return $haystack[$needle];
    }
}
