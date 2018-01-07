<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title','')</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="{{BOOTSTRAP}}/css/bootstrap.min.css" media="all">
    <link rel="stylesheet" type="text/css" href="{{LAYUI}}/css/layui.css" media="all">
    <link rel="stylesheet" type="text/css" href="{{ADMINUI}}/css/global.css" media="all">
    <style>
        .btn:focus,.btn:active{
            outline: none!important;
            border-color: transparent!important;
        }
    </style>
    @yield('css')

</head>
<body>
@yield('content')

<script type="text/javascript" src="{{COMNJS}}/jquery.min.js"></script>
<script type="text/javascript" src="{{BOOTSTRAP}}/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{{LAYUI}}/layui.js"></script>
<script>
    //模态框表单提交
    function submitFdata(obj){
        var url = $(obj).data('url');
        var data = $(obj).parents('.modal-content').find('form').serialize();
            $.post(url,data,function(res){
                layui.use(['layer'],function(){
                    if(res.code > 0){
                        layer.msg(res.msg,{maxWidth:260});
                    }else{
                        layer.msg('操作成功',{maxWidth:260,time:1000},function(){
                            window.location.reload();
                        });
                    }
                })
            },'json');
    }
    //普通表单提交
    function submitNdata(obj,params){
        var url = $(obj).data('url');
        var jurl = $(obj).data('jurl');
        var data = $(obj).parents('form').serialize();
        if(params && (JSON.stringify(params) != '{}')){
            data = data+'&'+$.param(params);
        }
        $.post(url,data,function(res){

            layui.use(['layer'],function(){
                if(res.code > 0){
                    layer.msg(res.msg,{maxWidth:260});
                }else{
                    layer.msg('操作成功',{maxWidth:260,time:1000},function(){
                        window.location.href = jurl;
                    });
                }
            })
        },'json');
    }

    //含有iframe的表单
    function submitIdata(obj){
        var url = $(obj).data('url');
        var jurl = $(obj).data('jurl');
        var data = $(obj).parents('form').serialize();
        $(obj).parents('form').find('iframe').contents().find('form').each(function(){
            data += '&'+$(this).serialize();
        });
        $.post(url,data,function(res){
            layui.use(['layer'],function(){
                if(res.code > 0){
                    layer.msg(res.msg);
                }else{
                    layer.msg('操作成功',{time:1000},function(){
                        window.location.href = jurl;
                    });
                }
            });
        },'json');
    }
    //table表删除
    function getpdel(url,field,fVal){
        var data = new Object();
        data[field] = fVal;
        layui.use('layer',function(){
            layer.open({
                title:'提示',
                icon:3,
                content:'确认删除',
                anim:3,
                maxWidth:260,
                btn:['确认','取消'],
                btn1:function(index){
                    $.post(url,data,function(res){
                        if(res.code > 0){
                            layer.msg(res.msg,{icon:2});
                        }else{
                            window.location.reload();
                        }
                    },'json')
                },
                btn2:function(index){
                    layer.close(index);
                }
            });
        });
    }
</script>
@yield('js')
</body>
</html>