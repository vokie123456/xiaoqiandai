<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{$admin_name}}后台登录</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <!-- load css -->
    <link rel="icon" href="{{ADMINUI}}/images/theme/laravel.png" type="image/x-icon"/>
    <link rel="stylesheet" type="text/css" href="{{LAYUI}}/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="{{ADMINUI}}/css/login.css" media="all">
</head>
<body>
<div class="layui-canvs"></div>
<div class="layui-layout layui-layout-login">
    <h1>
        <strong>{{$admin_name}}后台管理系统</strong>
        <em>Management System</em>
    </h1>
    <form action="javascript:void(0);">
        <div class="layui-user-icon larry-login">
            <input type="text" name="account" placeholder="账号" value="{{Cookie::get('account')}}" class="login_txtbx"/>
        </div>
        <div class="layui-pwd-icon larry-login">
            <input type="password" name="password" placeholder="密码" class="login_txtbx"/>
        </div>
        <div class="layui-val-icon larry-login">
            <div class="layui-code-box">
                <input type="text" id="code" name="code" placeholder="验证码" maxlength="4" class="login_txtbx">
                <img src="{{$verify}}" alt="" class="verifyImg" id="verifyImg" onclick="getVerify(this);">
            </div>
        </div>
        <div class="layui-submit larry-login">
            <input type="submit"  onclick="postLogin(this)" value="立即登陆" class="submit_btn"/>
        </div>
    </form>
    <div class="layui-login-text">
        <a target="_blank" href="http://www.somorn.com"><p>© {{$admin_name}} 技术支持</p></a>
    </div>
</div>
<script src="{{COMNJS}}/jquery.min.js"></script>
<script type="text/javascript" src="{{LAYUI}}/lay/dest/layui.all.js"></script>
<script type="text/javascript" src="{{ADMINUI}}/jsplug/jparticle.jquery.js"></script>
<script type="text/javascript">

    layui.use(['jquery'],function(){
        window.jQuery = window.$ = layui.jquery;
        $(".layui-canvs").width($(window).width());
        $(".layui-canvs").height($(window).height());

    });

    //刷新验证码图片
    function getVerify(obj){
        var theme = "{{strtolower($theme->name)}}";
        var url = "{{u('admin/public/getVerify')}}";
        $.get(url,{theme:theme},function(res){
            $(obj).attr('src',res.verify);
        },'json');
    }
    //提交登录信息
    function postLogin(obj){
        var url = "{{u('admin/public/postLogin')}}";
        var data = $(obj).parents('form').serialize();
        $.post(url,data,function(res){
            if(res.code > 0){
                getVerify("#verifyImg");
                layer.msg(res.msg);
            }else{
                location.href = "{{u('admin')}}";
            }
        },'json');
    }
    $(function(){
        $(".layui-canvs").jParticle({
            background: "#141414",
            color: "#E6E6E6"
        });
        var overdue = parseInt({{empty(session('overdue'))?false:true}});
        if(overdue){
            window.parent.location.href="{{u('admin/public/login')}}";
        }
    });

</script>
</body>
</html>