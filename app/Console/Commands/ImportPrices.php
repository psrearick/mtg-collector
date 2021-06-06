<?php

namespace App\Console\Commands;

use App\Jobs\ImportCardPrices;
use Illuminate\Console\Command;

class ImportPrices extends Command
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
    protected $signature = 'import:prices';

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
        $job = new ImportCardPrices;
        $job->handle();
    }
}
