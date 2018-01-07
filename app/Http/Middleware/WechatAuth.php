<?php

namespace App\Http\Middleware;

use Closure,Session,Response;


class WechatAuth
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
       $wx_session = Session::get($guard.'_id');
       //登录url
       $loginUrl = 'wechat/'.$guard.'/login';
       if(empty($wx_session)){
           if($request->ajax()){
                return Response::json(['code' => 777,'msg' =>'请授权登录']);
           }
           Session::flash('return_url',$request->fullUrl());
           return redirect($loginUrl);
       }
       return $next($request);
    }


}
