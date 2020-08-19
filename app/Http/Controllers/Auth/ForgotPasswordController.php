<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    use \App\Http\Controllers\BasicController;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest','feature_available:enable_reset_password']);
    }

    public function showLinkRequestForm()
    {
        $assets = ['recaptcha'];
        return view('auth.passwords.email',compact('assets'));
    }

    public function sendResetLinkEmail(Request $request)
    {
        if(config('config.enable_recaptcha') && config('config.enable_recaptcha_reset_password')){
            $gresponse = $this->recaptchaResponse($request);
            if(!$gresponse['success'])
                return response()->json(['message' => trans('messages.verify_recaptcha'), 'status' => 'error']);
        }

        $this->validate($request, ['email' => 'required|email']);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT)
            return response()->json(['message' => trans($response), 'status' => 'success']);

        return response()->json(['message' => trans($response), 'status' => 'error']);
    }
}
