<?php

namespace App\App\Console\Commands;

use App\Jobs\ImportScryfallData;
use Illuminate\Console\Command;

class ImportSets extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import set data from Scryfall';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:sets';

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
        $options = [
            'prices'    => false,
            'symbols'   => false,
            'cards'     => false,
            'sets'      => true,
        ];

        ImportScryfallData::dispatch($options)->onQueue('long-running-queue');

        return Command::SUCCESS;
    }
}
