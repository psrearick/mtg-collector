<?php

namespace App\App\Console\Commands;

use App\Jobs\ImportScryfallData;
use Illuminate\Console\Command;

class ImportScryfall extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get scryfall card data';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:scryfall';

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
        // $job = new ImportScryfallData;
        // $job->handle();

        ImportScryfallData::dispatch()->onQueue('long-running-queue');

        return true;
    }
}
