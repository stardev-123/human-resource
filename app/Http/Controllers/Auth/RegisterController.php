<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use App\Notifications\ActivationToken;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use \App\Http\Controllers\BasicController;
    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
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
        $this->middleware(['guest','feature_available:enable_user_registration'])->only('showRegistrationForm');
    }

    public function showRegistrationForm()
    {
        $assets = ['recaptcha'];
        return view('auth.register',compact('assets'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validation_messages = [
            'user.regex' => trans('messages.username_rules'),
            'password.regex' => trans('messages.password_rules'),
        ];

        $rules = [
            'email' => 'required|email|max:255|unique:users',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'username' => 'required|min:4|max:255|unique:users|'.usernameRule(),
            'password' => 'required|min:6|confirmed|'.passwordRule(),
            'password_confirmation' => 'required',
            'designation_id' => 'sometimes|required',
            'role_id' => 'sometimes|required',
            'date_of_joining' => 'sometimes|required|date',
            'employee_code' => 'sometimes|required|unique:profiles'
        ];

        $niceNames = array();
        $niceNames = [
            'email' => trans('messages.email'),
            'first_name' => trans('messages.first').' '.trans('messages.name'),
            'last_name' => trans('messages.last').' '.trans('messages.name'),
            'username' => trans('messages.username'),
            'password' => trans('messages.password'),
            'password_confirmation' => trans('messages.password').' '.trans('messages.confirmation'),
            'designation_id' => trans('messages.designation'),
            'role_id' => trans('messages.role')
        ];

        if(config('config.enable_tnc') && !\Auth::check()){
            $rules['tnc'] = 'accepted';
            $niceNames = [
                'tnc' => 'terms and conditions'
            ];
        }

        $validator = Validator::make($data, $rules,$validation_messages);
        $validator->setAttributeNames($niceNames); 

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if(config('config.enable_email_verification') && !\Auth::check()){
            $activation_token = randomString(30);
            $user->activation_token = $activation_token;
            $user->status = 'pending_activation';
            $user->save();
        } elseif(config('config.enable_account_approval') && !\Auth::check()) {
            $user->status = 'pending_approval';
            $user->save();
        } else {
            $user->status = 'active';
            $user->save();
        }

        return $user;
    }

    public function register(Request $request)
    {
        
        if(!\App\Role::whereIsDefault(1)->count() && !\Auth::check())
            return response()->json(['message' => trans('messages.no_default_role_for_user'), 'status' => 'error']);

        if(!\App\Designation::whereIsDefault(1)->count() && !\Auth::check())
            return response()->json(['message' => trans('messages.no_default_designation_for_user'), 'status' => 'error']);

        $this->validator($request->all())->validate();

        if(config('config.enable_recaptcha') && config('config.enable_recaptcha_registration')){
            $gresponse = $this->recaptchaResponse($request);
            if(!$gresponse['success'])
                return response()->json(['message' => trans('messages.verify_recaptcha'), 'status' => 'error']);
        }

        $validation = validateCustomField('user-registration-form',$request);

        if($validation->fails())
            return response()->json(['message' => $validation->messages()->first(), 'status' => 'error']);

        event(new Registered($user = $this->create($request->all())));

        $role = \App\Role::whereIsDefault(1)->first();
        $user->roles()->sync(($request->input('role_id')) ? explode(',',$request->input('role_id')) : (isset($role) ? [$role->id] : []));

        $designation = \App\Designation::whereIsDefault(1)->first();
        $date_of_joining = ($request->has('date_of_joining')) ? $request->input('date_of_joining') : date('Y-m-d');
        $designation_id = ($request->has('designation_id')) ? $request->input('designation_id') : $designation->id;

        $user_designation = new \App\UserDesignation;
        $user_designation->designation_id = $designation_id;
        $user_designation->from_date = $date_of_joining;
        $user_designation->user_id = $user->id;
        $user_designation->save();

        $user_employment = new \App\UserEmployment;
        $user_employment->date_of_joining = $date_of_joining;
        $user_employment->user_id = $user->id;
        $user_employment->save();

        $profile = new \App\Profile;
        $profile->employee_code = ($request->has('employee_code')) ? $request->input('employee_code') : null;
        $profile->first_name = $request->input('first_name');
        $profile->last_name = $request->input('last_name');
        $profile->designation_id = ($request->input('designation_id') ? : ((isset($designation) && !\Auth::check()) ? $designation->id : null));
        $user->profile()->save($profile);
        
        if(config('config.enable_email_verification') && !\Auth::check())
            $user->notify(new ActivationToken($user));

        if($request->has('send_welcome_email')){
            $template = \App\Template::whereSlug('welcome-email')->first();
            $body = isset($template->body) ? $template->body : 'Hello [NAME], Welcome to '.config('config.application_name');
            $body = str_replace('[NAME]',$user->full_name,$body); 
            $body = str_replace('[PASSWORD]',$request->input('password'),$body);
            if(!config('config.login'))
            $body = str_replace('[USERNAME]',$user->username,$body);
            $body = str_replace('[EMAIL]',$user->email,$body);

            $mail['email'] = $user->email;
            $mail['subject'] = $template->subject;

            \Mail::send('emails.email', compact('body'), function($message) use ($mail){
                $message->to($mail['email'])->subject($mail['subject']);
            });
            $this->logEmail(array('to' => $mail['email'],'subject' => $mail['subject'],'body' => $body));
        }

        $data = $request->all();
        storeCustomField('user-registration-form',$user->id, $data);

        $response = ['message' => trans('messages.user_registered'), 'status' => 'success'];
        if(!\Auth::check())
            $response['redirect'] = '/login';

        return response()->json($response);
    }
}
