<?php

namespace App\Console;

use App\Models\User;
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
            ->weeklyOn(7, '01:00')
            ->emailOutputOnFailure(User::siteAdminEmails());

        $schedule->command('fetch:laws oregon 30')
            ->weeklyOn(7, '02:00')
            ->emailOutputOnFailure(User::siteAdminEmails());

        $schedule->command('fetch:laws idea')
            ->weeklyOn(7, '03:00')
            ->emailOutputOnFailure(User::siteAdminEmails());
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
