<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }

        if($request->is('login') && strtolower($request->method()) == 'post' && getMode()){
            if(is_connected()){
                $data = verifyPurchase();
                if($data['status'] == 'error'){
                    envu(['PURCHASE_CODE' => '']);
                    return response()->json(['message' => $data['message'],'redirect' => '/verify-purchase','status' => 'error']);
                }
            }
        }
        
        return $next($request);
    }
}
