<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('email:fetch')->everyFiveMinutes(); // ✅ Run email fetching every 5 minutes
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
    }
}
