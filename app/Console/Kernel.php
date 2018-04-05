<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        '\App\Console\Commands\CheckDueActivity',
        //'\App\Console\Commands\ImportOrder',
        '\App\Console\Commands\UpdateBusinessCalendar',
        '\App\Console\Commands\ConfirmationBeforeDelivery',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        Log::info('Kernel::schedule -- Start.');

        // Run sending email once a minute
        //$schedule->command('queue:work', ['--timeout=60', '--tries=5'])->cron('* * * * * *');
        $schedule->command('queue:work', ['--timeout=60', '--tries=5'])->everyMinute();

        // Run at 01:00 every morning
        $schedule->command('CheckDueActivity:notifyUsers')->dailyAt('01:00');

        // Run every minute to import new orders
        // No need to execute this. Changed to create CPM on the fly
        // $schedule->command('ImportOrder:createCPMActivities')->everyMinute();

        // Run monthly to update public holidays
        $schedule->command('UpdateBusinessCalendar:updateHolidays')->monthly();

        // Run every day to confirm the delivery date 2 days before delivery date
        $schedule->command('ConfirmationBeforeDelivery:confirmDeliveryDate')->dailyAt('03:00');

//        // Run every 5 minutes
//        $schedule->command('queue:work')->everyFiveMinutes();
//
//        // Run once a day
//        $schedule->command('queue:work')->daily();
//
//        // Run Mondays at 8:15am
//        $schedule->command('queue:work')->weeklyOn(1, '8:15');
        Log::info('Kernel::schedule -- End.');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
