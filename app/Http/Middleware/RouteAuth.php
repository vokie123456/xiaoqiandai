<?php

namespace App\Http\Middleware;

use App\Models\Adminer;
use App\Models\Permission;
use App\Models\Role;
use Closure,Session,Response;



class RouteAuth
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
        $identity = Session::get('identity');
        if($identity['role_id']){
            $path = $request->path();
            $permiss = Permission::where('route',$path)->first();
            if(empty($permiss)){
                if($request->ajax()){
                    return Response::json(['code' => 999,'msg' =>'缺少此权限']);
                }else{
                    $prepage = $request->header()['referer'][0];
                    return Response::view('admin.index.nopermiss',['prepage' => $prepage]);
                }
            }
            //获取当前角色所有权限  和当前请求路由对应的权限id
            $role = Role::where('id',$identity['role_id'])->first();
            if(!in_array($permiss->id,unserialize($role->permiss_ids))){
                if($request->ajax()){
                    return Response::json(['code' => 999,'msg' =>'您没有此权限']);
                }else{
                    $prepage = $request->header()['referer'][0];
                    return Response::view('admin.index.nopermiss',['prepage' => $prepage]);
                }
            }
        }
        return $next($request);
    }
}
