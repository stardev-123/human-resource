<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    
    use \App\Http\Controllers\BasicController;

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        $assets = ['recaptcha'];
        return view('auth.login',compact('assets'));
    }

    public function login(Request $request)
    {
        if(config('config.enable_recaptcha') && config('config.enable_recaptcha_login')){
            $gresponse = $this->recaptchaResponse($request);
            if(!$gresponse['success'])
                return response()->json(['message' => trans('messages.verify_recaptcha'), 'status' => 'error']);
        }

        if(config('config.enable_two_factor_auth') && !config('config.two_factor_auth_type') && (!config('config.nexmo_api_key') || !config('config.nexmo_api_secret') || !config('config.nexmo_from_number')))
                return response()->json(['message' => trans('messages.two_factor_sms_not_configured'), 'status' => 'error']);

        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function username()
    {
        if(config('config.login_type') == 'email')
            $field = 'email';
        elseif(config('config.login_type') == 'username')
            $field = 'username';
        else
            $field = 'email';
        return $field;
    }

    protected function credentials(Request $request)
    {
        if(config('config.login_type') == 'username_or_email') {
            $field = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $request->merge([$field => $request->input('email')]);
        } else 
        $field = config('config.login_type');
        return $request->only($field, 'password');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return response()->json(['status' => 'success','redirect' => '/home']);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        if($request->has('ajax_submit'))
            return response()->json(['message' => trans('auth.failed'), 'status' => 'error']);
    }

}
