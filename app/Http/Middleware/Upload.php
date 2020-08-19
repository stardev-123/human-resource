<?php
namespace App\Http\Middleware;
use Closure;

class Upload
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
        $modules = ['job-application'];

        if(\Auth::check() || in_array($request->input('module'),$modules))
            return $next($request);
        else
            return response()->json(['message' => trans('messages.invalid_link'),'status' => 'error','redirect' => '/login']);
    }
}
