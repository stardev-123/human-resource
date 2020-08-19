<?php
namespace App\Http\Middleware;
use Closure;

class FeatureAvailable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$feature)
    {
        if(config('config.'.$feature))
            return $next($request);
        else
            return redirect('/login')->withErrors(trans('messages.invalid_link'));
    }
}
