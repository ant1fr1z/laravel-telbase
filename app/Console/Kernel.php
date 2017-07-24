<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
     * ФУНКЦИЯ
     * ФОРМИРОВАНИЯ
     * СТАТИСТИКИ
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $dayCount = DB::table('objects')->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->orWhereBetween('updated_at', [Carbon::now()->subDay(), Carbon::now()])->count();
            $weekCount = DB::table('objects')->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])->orWhereBetween('updated_at', [Carbon::now()->subWeek(), Carbon::now()])->count();
            $monthCount = DB::table('objects')->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])->orWhereBetween('updated_at', [Carbon::now()->subMonth(), Carbon::now()])->count();
            DB::table('statistics')->where('key', 'day_count')->update(['intValue' => $dayCount]);
            DB::table('statistics')->where('key', 'week_count')->update(['intValue' => $weekCount]);
            DB::table('statistics')->where('key', 'month_count')->update(['intValue' => $monthCount]);
        })->daily();
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
