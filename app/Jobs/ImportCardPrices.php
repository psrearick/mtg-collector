<?php

namespace App\Jobs;

use App\Actions\DownloadFileAction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportCardPrices implements ShouldQueue
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Pricing data URL
        $file = [
            'url'           => "https://mtgjson.com/api/v5/AllPrices.json",
            'format'        => 'json',
            'storage_path'  => 'dumps/pricing',
        ];

        $save_file_loc = (new DownloadFileAction())->execute($file, 'Ymd', 5);
    }
}
