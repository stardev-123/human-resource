<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['guest','feature_available:enable_reset_password']);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();
    }

    protected function rules()
    {
        return [
            'token' => 'required', 'email' => 'required|email',
            'password' => 'required|confirmed|min:6|'.passwordRule(),
            'password_confirmation' => 'required'
        ];
    }

    protected function validationErrorMessages()
    {
        return [
            'password.regex' => trans('messages.password_alphanumeric'),
        ];
    }

    protected function sendResetResponse($response)
    {
        return response()->json(['message' => trans($response), 'status' => 'success','redirect' => '/home']);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => trans($response), 'status' => 'error']);
    }
}
