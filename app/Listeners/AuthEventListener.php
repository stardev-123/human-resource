<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use App\Notifications\TwoFactorAuth;

class AuthEventListener
{
    use \App\Http\Controllers\BasicController;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SomeEvent  $event
     * @return void
     */
    public function login($event)
    {
        if(!session()->has('parent_login') && \Auth::user()->status == 'active' && \Auth::user()->designation_name){
            \Auth::user()->last_login = \Auth::user()->last_login_now;
            \Auth::user()->last_login_ip = \Auth::user()->last_login_ip_now;
            \Auth::user()->last_login_now = new \DateTime;
            \Auth::user()->last_login_ip_now = \Request::getClientIp();
            \Auth::user()->save();
            if(config('config.enable_two_factor_auth')){
                $code = rand('100000','999999');
                session(['two_factor_auth' => $code]);
                if(getMode())
                \Auth::user()->notify(new TwoFactorAuth($code));
            }

            if(!config('config.enable_two_factor_auth') && config('config.enable_attendance_auto_clock')){
                $url = url('/clock/in');
                $postData = array(
                    'datetime' => date('Y-m-d H:i:s'),
                    'user_id' => \Auth::user()->id,
                    'api' => 1
                );
                postCurl($url,$postData);
            }
            $this->logActivity(['module' => 'login','activity' => 'logged_in']);
        } 
    }

    public function logout($event)
    {
        session()->forget('two_factor_auth');

        if(!session()->has('parent_login') && config('config.enable_attendance_auto_clock')){
            $url = url('/clock/in');
            $postData = array(
                'datetime' => date('Y-m-d H:i:s'),
                'user_id' => \Auth::user()->id,
                'api' => 1
            );
            postCurl($url,$postData);
        }
            
        $this->logActivity(['module' => 'logout','activity' => 'logged_out']);
        session()->forget('parent_login');
    }
}
