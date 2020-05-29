<?php

namespace App\Console;

use App\Jobs\SendScheduledTopups;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\RemoveOldLogs;

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
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sync:topups')->runInBackground();
        $schedule->command('process:files')->runInBackground()->withoutOverlapping(30);
        $schedule->command('sync:countries')->daily()->runInBackground();
        $schedule->command('sync:operators')->daily()->runInBackground();
        $schedule->command('sync:tokens')->daily()->runInBackground();
        $schedule->job(RemoveOldLogs::class)->daily()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
