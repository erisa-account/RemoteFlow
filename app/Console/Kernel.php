<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('leave:year-rollover')
            ->yearlyOn(1, 1, '00:01');

        // Expire carried leave (example: April 1)
        $schedule->command('leave:expire-carry')
            ->yearlyOn(4, 1, '00:01');
    }
    
    protected $commands = [
    \App\Console\Commands\ProcessYearRollover::class,
    \App\Console\Commands\ExpireCarriedLeave::class,
     ];

    /**
     * Register the commands for the application
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
