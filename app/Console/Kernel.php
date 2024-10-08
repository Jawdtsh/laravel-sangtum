<?php

namespace App\Console;

use App\Console\Commands\DeleteExpiredTokenCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
         $schedule->command('app:delete-expired-token-command')->everySixHours();
         $schedule->command('app:delete-expired-token-command')->everySecond();
         $schedule->command('orders:delete-delivered')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
