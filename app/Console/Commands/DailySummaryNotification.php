<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\SummaryNotification;
use App\Config;

class DailySummaryNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-summary-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends daily summary notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        setConfig(Config::all());
        foreach(\App\User::whereStatus('active')->get() as $user){
            $notifications = \App\Notification::whereRaw('FIND_IN_SET(?,user)', [$user->id])->whereRaw('NOT FIND_IN_SET(?,user_read)', [$user->id])->get();
            if($notifications->count())
                $user->notify(new SummaryNotification($notifications,$user));
        }
    }
}
