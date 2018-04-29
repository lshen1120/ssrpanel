<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;

class Mu
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
        $muKey = $request->get("key",'');
        if($muKey!=$_ENV['MU_KEY']){
            return response()->json([
                'ret'=>0,
                'msg'=>'token is  invalid'
            ]);
        }

        return $next($request);
    }
}
