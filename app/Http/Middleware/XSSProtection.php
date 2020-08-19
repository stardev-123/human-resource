<?php
namespace App\Http\Middleware;

class XSSProtection
{
    /**
     * The following method loops through all request input and strips out all tags from
     * the request. This to ensure that users are unable to set ANY HTML within the form
     * submissions, but also cleans up input.
     *
     * @param Request $request
     * @param callable $next
     * @return mixed
     */

    public function handle($request, \Closure $next)
    {

        $except = array();
        foreach(config('xss') as $key => $value){
            if($request->is($key)){
                $except = $value;
            }
        }

        $input = $request->except($except);

        array_walk_recursive($input, function(&$input) {
            $input = strip_tags($input);
        });

        $request->merge($input);

        return $next($request);
    }
}