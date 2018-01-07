<?php

namespace App\Http\Middleware;

use App\Models\Adminer;
use Closure,Session,Response;


class LogonAuth
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
        if($guard == 'wechat'){
           $wxuser_id = Session::get('wxuser_id');
           if(empty($wxuser_id)){
               if($request->ajax()){
                    return Response::json(['code' => 777,'msg' =>'登录已过期']);
               }

               return redirect('wechat');
           }
        }
        if($guard == 'admin'){
            $identity = Session::get('identity');
            $adminer = Adminer::where('id',$identity['adminer_id'])->first();
                if(empty($identity['token']) || empty($adminer->token)){
                    if($request->ajax()){
                        return Response::json(['code' => 999,'msg' =>'登录已过期']);
                    }else{
                        return redirect('admin/public/login')->with('overdue',1);
                    }
                }
                if($identity['token'] != $adminer->token){
                    if($request->ajax()){
                        return Response::json(['code' => 999,'msg' =>'登录已过期']);
                    }else{
                        return redirect('admin/public/login')->with('overdue',1);
                    }
                }
        }

        return $next($request);
    }


}
