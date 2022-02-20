<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('fetch:laws washington 28A')
            ->dailyAt('00:00')
            ->emailOutputOnFailure('adamjbailey7@gmail.com');

        $schedule->command('fetch:laws oregon 30')
            ->dailyAt('01:00')
            ->emailOutputOnFailure('adamjbailey7@gmail.com');

        $schedule->command('fetch:laws idea')
            ->dailyAt('02:00')
            ->emailOutputOnFailure('adamjbailey7@gmail.com');
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
