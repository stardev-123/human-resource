<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class Account
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $profile = Auth::user()->Profile;

        if(!isset($profile) && $profile == '' && $profile == null){
            $profile = new \App\Profile;
            $profile->user()->associate(Auth::user());
            $profile->save();
        }
        
        $user = \Auth::user();

        if(!defaultRole()){
            $my_designation = getUserDesignation(date('Y-m-d'));
            if(!$my_designation){
                $profile->designation_id = null;
                $profile->save();
                \Auth::logout();
                return redirect('/login')->withErrors(trans('messages.user_designation_not_defined'));
            } else
                $profile->designation_id = $my_designation;
            $profile->save();
        }

        $user_employment = getEmployment();
        if(!defaultRole() && !$user_employment){
            $user->status = 'inactive' ;
            $user->save();
        }

        if(!\Entrust::can('enable-login') || Auth::user()->disable_login)
            $error_message = trans('messages.login_disabled');
        if(Auth::user()->status == 'pending_approval')
            $error_message = trans('messages.account_not_approved');
        elseif(Auth::user()->status == 'pending_activation')
            $error_message = trans('messages.account_not_activated');
        elseif(Auth::user()->status == 'inactive')
            $error_message = trans('messages.account_inactive');
        elseif(Auth::user()->status == 'banned')
            $error_message = trans('messages.account_banned');
        elseif(config('config.maintenance_mode') && !defaultRole() && Auth::check() && !$request->is('under-maintenance'))
            $error_message = trans('messages.under_maintenance_message');
        elseif(getMode() && config('config.enable_ip_filter') && \App\IpFilter::count() && !validateIp() && Auth::check() && defaultRole())
            $error_message = trans('messages.ip_not_allowed');

        if(isset($error_message)){
            Auth::logout();
            if($request->ajax())
                return response()->json(['message' => $error_message, 'status' => 'error']);
            else 
                return redirect('/login')->withErrors($error_message);
        }
        
        if(config('config.enable_two_factor_auth') && session()->has('two_factor_auth') && !$request->is('verify-security')){
            if($request->ajax())
                return response()->json(['message' => config('config.redirecting_message'), 'status' => 'success','redirect' => '/verify-security' ]);
            else
                return redirect('/verify-security');
        }

        return $next($request);
    }
}
