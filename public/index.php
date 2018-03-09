<?php
echo "xiaoqiandai";die();

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/') + 1));
define('WECHAT_UI', BASE_URL . 'wechatui');   //ui组件
define('LAYUI', BASE_URL . 'layui');   //LAYERui组件
define('ADMINUI', BASE_URL . 'adminui');   //后台ui组件
define('BOOTSTRAP', BASE_URL . 'bootstrapui');   //后台ui组件
define('COMNJS', BASE_URL . 'js');   //公用js
define('COMNCSS', BASE_URL . 'css');   //公用CSS
define('DEFAULT_IMG', BASE_URL . 'images/user.png');   //公用CSS
define('LAYDATE', BASE_URL . 'laydate');   //laydate时间插件
define('COMIMG', BASE_URL . 'images');   //公用img
define('ICHECK', BASE_URL . 'icheckui');   //icheckui
define('WEDITOR', BASE_URL . 'wangEditor');   //wangEditor

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
