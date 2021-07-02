<?php

namespace App\App\Console\Commands;

use App\Jobs\ImportCardDataPrices;
use Illuminate\Console\Command;

class ImportProviderPrices extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:providerprices';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $job = new ImportCardDataPrices;
        $job->handle();
    }
}
