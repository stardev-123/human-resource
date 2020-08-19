<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\ActivationToken;

class ActivateController extends Controller
{
    public function __construct()
    {
        $this->middleware('feature_available:enable_email_verification');
    }

    public function resendActivation(){
    	return view('auth.resend_activation');
    }

    public function postResendActivation(Request $request){

        $user = \App\User::whereEmail($request->input('email'))->first();

        if(!$user)
            return response()->json(['message' => trans('messages.no_user_with_email'), 'status' => 'error']);
        elseif($user->status != 'pending_activation')
            return response()->json(['message' => trans('messages.account_already_activated'), 'status' => 'error']);
        $user->notify(new ActivationToken($user));
        return response()->json(['message' => trans('messages.activation_email_sent'), 'status' => 'success']);
    }

    public function activateAccount($token){

        if($token == null)
            return redirect('/login');

        $user = \App\User::whereActivationToken($token)->first();

        if(!$user)
            return redirect('/login')->withErrors(trans('messages.invalid_link'));

        if($user->status != 'pending_activation')
            return redirect('/login')->withErrors(trans('messages.invalid_link'));

        $user->status = (config('config.enable_account_approval')) ? 'pending_approval' : 'active';
        $user->save();
        return redirect('/login')->withSuccess(trans('messages.account_activated'));
    }
}