<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 16:08
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;


class AuthController extends BaseController
{

   function __construct(Request $request){

       parent::__construct($request);

       $this->middleware('logonauth:admin'); //登录中间件

       $this->middleware('routeauth');   //路由权限中间件
   }


}