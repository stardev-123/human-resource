<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Config;

class SendWish extends Command
{
    use \App\Http\Controllers\BasicController;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-wish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Birthday & Anniversary Wishes to Staff';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        setConfig(Config::all());
        $default_timezone = config('config.timezone_id') ? config('timezone.'.config('config.timezone_id')) : 'Asia/Kolkata';
        date_default_timezone_set($default_timezone);

        $birthdays = \App\Profile::whereRaw('MONTH(date_of_birth) = MONTH(NOW()) AND DAY(date_of_birth) = DAY(NOW())')->get();

        foreach($birthdays as $birthday){
            $slug = 'birthday-email';

            $mail_data = $this->templateContent(['slug' => $slug,'user' => $birthday->User]);

            if(count($mail_data)){
                $mail['email'] = $birthday->User->email;
                $mail['subject'] = $mail_data['subject'];
                $body = $mail_data['body'];
                \Mail::send('emails.email', compact('body'), function($message) use ($mail){
                    $message->to($mail['email'])->subject($mail['subject']);
                });
                $this->logEmail(array('to' => $mail['email'],'subject' => $mail['subject'],'body' => $body,'module' => 'user','module_id' => $birthday->user_id));
            }
        }

        $anniversaries = \App\Profile::whereRaw('MONTH(date_of_anniversary) = MONTH(NOW()) AND DAY(date_of_anniversary) = DAY(NOW())')->get();

        foreach($anniversaries as $anniversary){
            $slug = 'anniversary-email';

            $mail_data = $this->templateContent(['slug' => $slug,'user' => $anniversary->User]);

            if(count($mail_data)){
                $mail['email'] = $anniversary->User->email;
                $mail['subject'] = $mail_data['subject'];
                $body = $mail_data['body'];
                \Mail::send('emails.email', compact('body'), function($message) use ($mail){
                    $message->to($mail['email'])->subject($mail['subject']);
                });
                $this->logEmail(array('to' => $mail['email'],'subject' => $mail['subject'],'body' => $body,'module' => 'user','module_id' => $anniversary->user_id));
            }

        }
    }
}