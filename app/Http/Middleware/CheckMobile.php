<?php

namespace App\Http\Middleware;


use Closure,Response;



class CheckMobile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(isMobile()){
            return redirect('wechat');
        }else{
            return redirect('admin');
        }
        return $next($request);
    }
}
