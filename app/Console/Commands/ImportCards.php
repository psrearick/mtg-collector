<?php

namespace App\Console\Commands;

use App\Jobs\ImportCardData;
use Illuminate\Console\Command;
use pcrov\JsonReader\Exception;

class ImportCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:cards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run card import job';

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
     * @throws Exception
     */
    public function handle()
    {
        $job = new ImportCardData;
        $job->handle();
    }
}
