<?php

namespace App\App\Console;

use App\Jobs\GenerateApiMap;
use App\Jobs\ImportCardDataPrices;
use App\Jobs\ImportScryfallData;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule
            ->job(new GenerateApiMap())
            ->weeklyOn(6, '1:00');
        $schedule->job(new ImportCardDataPrices())
            ->dailyAt('3:30');
        $schedule->job(new ImportScryfallData())
            ->dailyAt('4:30');
    }
}
