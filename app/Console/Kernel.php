<?php

namespace App\Console;

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
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $Users = DB::table('users')->where('is_admin',0)->where('is_new_register',1)->get();
            if(!$Users->isEmpty())
            {
                foreach ($Users as $key => $User) {
                    $is_paid = 0;
                    $buy_packages_count = DB::table('transaction')->where('user_id',$User->id)->where('expiry_date','>',date('Y-m-d H:i:s'))->count();
                    if($buy_packages_count > 0)
                    {
                        $is_paid = 1;
                    }
                    DB::table('users')->where('id',$User->id)->update(['is_paid'=>$is_paid]);
                }
            }
        })->everyThirtyMinutes();
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
