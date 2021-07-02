<?php


namespace App\App\Console\Commands;


use App\Jobs\GenerateApiMap;
use Illuminate\Console\Command;

class ImportApiMap extends Command
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an API map with MTGJson data';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:apimap';

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
        $job = new GenerateApiMap;
        $job->handle();

        return true;
    }
}
