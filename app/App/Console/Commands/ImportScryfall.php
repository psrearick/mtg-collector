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
    protected $signature = 'import:scryfall {--P|no-prices} {--Y|no-symbols} {--C|no-cards} {--S|no-sets}';

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
            'prices'    => !$this->option('no-prices'),
            'symbols'   => !$this->option('no-symbols'),
            'cards'     => !$this->option('no-cards'),
            'sets'      => !$this->option('no-sets'),
        ];

        ImportScryfallData::dispatch($options)->onQueue('long-running-queue');

        return true;
    }
}
