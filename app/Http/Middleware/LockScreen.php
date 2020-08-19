<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Session;
 
class LockScreen {
 
    /**
     * Check session data, if role is not valid logout the request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if(!Auth::check() || !config('config.enable_lock_screen') || $request->is('logout') || $request->has('lock'))
            return $next($request);

        $max = config('config.lock_screen_timeout') * 60;

        if(!session()->has('last_activity') || $max > (time() - session('last_activity')))
            session()->put('last_activity',time());

        if($request->is('lock'))
            return $next($request);

        if(session('locked'))
            return redirect('/lock');

        if ($max < (time() - session('last_activity'))) {
            session(['locked' => 1]);
            if($request->ajax())
                return response()->json(['message' => trans('messages.screen_lock'),'status' => 'error','redirect' => '/home']);

            return redirect('/lock');
        } 

        return $next($request);
    }
 
}