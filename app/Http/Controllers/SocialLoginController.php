<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Socialite;

Class SocialLoginController extends Controller{
    use BasicController;

    public function providerRedirect($provider = ''){

        if(!in_array($provider,config('constant.social_login_provider')))
            return redirect('/login')->trans('messages.invalid_link');

        if(!config('config.enable_social_login') || !config('config.enable_'.$provider.'_login'))
            return redirect('/login')->trans('messages.invalid_link');

        return Socialite::driver($provider)->redirect();
    }

    public function providerRedirectCallback($provider = '')
    {
        if(!config('config.enable_social_login') || !config('config.enable_'.$provider.'_login'))
            return redirect('/login')->trans('messages.invalid_link');

        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return Redirect::to('/auth/'.$provider);
        }

        $user_exists = \App\User::whereEmail($user->email)->first();

        if($user_exists)
            \Auth::login($user_exists, true);
        else {
            $new_user = new \App\User;
            $new_user->email = $user->email;
            $new_user->provider = $provider;
            $new_user->provider_unique_id = $user->id;
            $new_user->status = 'active';
            $new_user->save();
            $role = \App\Role::whereIsDefault(1)->first();
            $new_user->roles()->sync(isset($role) ? [$role->id] : []);
            $name = explode(' ',$user->name);
            $profile = new \App\Profile;
            $profile->user()->associate($new_user);
            $profile->first_name = array_key_exists(0, $name) ? $name[0] : 'John';
            $profile->last_name = array_key_exists(1, $name) ? $name[1] : 'Doe';
            $profile->save();
            \Auth::login($new_user, true);
        }
        return redirect('/home');
    }
}