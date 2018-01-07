<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{$admin_name}}后台</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">

    <link rel="icon" href="{{ADMINUI}}/images/theme/laravel.png" type="image/x-icon"/>
    <link rel="stylesheet" href="{{LAYUI}}/css/layui.css" media="all" />
    <link rel="stylesheet" href="{{ADMINUI}}/css/global.css" media="all">
    <link rel="stylesheet" type="text/css" href="{{ADMINUI}}/css/font-awesome.4.6.0.css">

</head>

<body>
<div class="layui-layout layui-layout-admin" style="border-bottom: solid 5px #1aa094;">
    <div class="layui-header header header-demo">
        <div class="layui-main">
            <div class="admin-login-box">
                <a class="logo" style="left: 0;" href="javscript:;">
                    <span style="font-size: 22px;"><img src="{{ADMINUI}}/images/adminlogo.png" ></span>
                </a>

            </div>
            <ul class="layui-nav admin-header-item">
                <li class="layui-nav-item" id="admin-side-toggle">
                    <a href="javascript:;">
                        <i class="fa fa-align-left" aria-hidden="true"></i>
                        左折</a>
                </li>
                <li class="layui-nav-item" id="admin-side-toggle">
                    <a href="javascript:history.back();">
                        <i class="fa fa-hand-o-left" aria-hidden="true"></i>
                        前页</a>
                </li>
                <li class="layui-nav-item" id="admin-side-toggle">
                    <a href="javascript:history.forward();">
                        <i class="fa fa-hand-o-right" aria-hidden="true"></i>
                        后页</a>
                </li>
                <li class="layui-nav-item" id="refresh_iframe">
                    <a href="javascript:;">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                        刷新</a>
                </li>

                <li class="layui-nav-item">
                    <a href="javascript:;" class="admin-header-user">
                        <img src="{{$adminer->head_img}}" />
                        <span>{{$adminer->name}}</span>
                    </a>
                    <dl class="layui-nav-child">

                        <dd id="lock">
                            <a href="javascript:;">
                                <i class="fa fa-lock" aria-hidden="true" style="padding-right: 3px;padding-left: 1px;"></i> 锁屏 (Alt+L)
                            </a>
                        </dd>
                        <dd>
                            <a href="{{u('admin/public/loginOut')}}"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a>
                        </dd>
                    </dl>
                </li>
            </ul>
            <ul class="layui-nav admin-header-item-mobile">
                <li class="layui-nav-item">
                    <a href="login.html"><i class="fa fa-sign-out" aria-hidden="true"></i> 注销</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="layui-side layui-bg-black" id="admin-side">
        <div class="layui-side-scroll" id="admin-navbar-side" lay-filter="side"></div>
    </div>
    <div class="layui-body" style="bottom: 0;border-left: solid 2px #1AA094;" id="admin-body">
        <div class="layui-tab admin-nav-card layui-tab-brief" lay-filter="admin-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    <cite>主页面</cite>
                </li>
            </ul>
            <div class="layui-tab-content" style="min-height: 150px; padding: 5px 0 0 0;">
                <div class="layui-tab-item layui-show">
                    <iframe src="{{u('admin/main')}}"></iframe>
                </div>
            </div>
        </div>
    </div>
    <div class="layui-footer footer footer-demo" id="admin-footer">
        <div class="layui-main">
            <p>2017 &copy;
                <a href="http://www.somorn.com/">{{$admin_name}}有限公司</a>
            </p>
        </div>
    </div>
    <div class="site-tree-mobile layui-hide">
        <i class="layui-icon">&#xe602;</i>
    </div>
    <div class="site-mobile-shade"></div>

    <!--锁屏模板 start-->
    <script type="text/template" id="lock-temp">
        <div class="admin-header-lock" id="lock-box">
            <div class="admin-header-lock-img">
                <img src="{{$adminer->head_img}}"/>
            </div>
            <div class="admin-header-lock-name" id="lockUserName">beginner</div>
            <input type="text" class="admin-header-lock-input" value="输入密码解锁.." name="lockPwd" id="lockPwd" />
            <button class="layui-btn layui-btn-small" id="unlock">解锁</button>
        </div>
    </script>
    <!--锁屏模板 end -->

    <script type="text/javascript" src="{{COMNJS}}/jquery.min.js"></script>
    <script type="text/javascript" src="{{LAYUI}}/layui.js"></script>

    <script>
        var navs = {!! json_encode($nav) !!};
        var unlockurl = "{{u('admin/index/unlock')}}";

    </script>
    <script type="text/javascript" src="{{ADMINUI}}/js/index.js"></script>
</div>
</body>
</html>