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
    <link rel="stylesheet" type="text/css" href="{{BOOTSTRAP}}/css/bootstrap.min.css" media="all">
    <link rel="stylesheet" type="text/css" href="{{ADMINUI}}/css/login.min.css" media="all">
</head>
<body class="signin">
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-7">
                <div class="signin-info">
                    <div class="logopanel m-b">
                    </div>
                    <div class="m-b"></div>
                    <h4>欢迎使用 <strong>{{$admin_name}}</strong></h4>
                    <ul class="m-b">

                    </ul>
                </div>
            </div>
            <div class="col-sm-5">
                <form method="post" action="javascript:;">
                    <p class="m-t-md" id="err_msg">登录到{{$admin_name}}</p>
                    <input type="text" style="font-weight: bold;" class="form-control uname" placeholder="用户名" name="account" value="{{Cookie::get('account')}}" />
                    <input type="password" style="font-weight: bold;" class="form-control pword" placeholder="密码" name="password" />
                    <div style="margin-top: 1em;" >
                        <input type="text" style="font-weight: bold;margin-left: 0px;width: 45%;display: inline-block;" class="form-control"  placeholder="验证码" maxlength="4"  name="code" id="code"/>
                        <img id="verifyImg" style="margin-left: 0.5em;height: 34px;" src="{{$verify}}" onclick="getVerify(this);" />
                    </div>
                    <div style="margin-top: 2em;">
                        <input class="btn btn-success btn-block"  type="submit" onclick="postLogin(this)" value="登录"/>
                    </div>
                </form>
            </div>
        </div>
        <div class="signup-footer">
            <div class="pull-left">
                © {{$admin_name}} 技术支持
            </div>
        </div>
    </div>


<script src="{{COMNJS}}/jquery.min.js"></script>
<script type="text/javascript" src="{{LAYUI}}/lay/dest/layui.all.js"></script>
<script type="text/javascript">
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
    var overdue = parseInt({{empty(session('overdue'))?false:true}});
    if(overdue){
        window.parent.location.href="{{u('admin/public/login')}}";
    }
</script>
</body>
</html>