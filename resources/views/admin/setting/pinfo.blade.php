@extends('admin.layouts.base')

@section('title','个人资料')
@section('css')
@endsection

@section('content')
    <div style="margin: 15px;">
        <form class="form-horizontal" role="form" action="javascript:;">
            <input type="hidden" name="id" value="">
            <div class="form-group">
                <label for="" class="control-label col-sm-3">头像 </label>
                <div class="col-sm-5">
                    <img style="width: 160px;" src="{{$adminer->head_img}}" onclick="upLoadPhotos()" id="head_img" class="img-circle">
                    <input type="file" id="headfile"  style="display: none;" />
                </div>
            </div>



            <div class="form-group">
                <label for="" class="control-label col-sm-3">账号 </label>
                <div class="col-sm-2">
                    <p  class="form-control" >{{$adminer->account}}</p>
                </div>
            </div>
            <div class="form-group Icon" >
                <label for="" class="control-label col-sm-3">管理员名 </label>
                <div class="col-sm-3">
                    <p  class="form-control" >{{$adminer->name}}</p>
                </div>
            </div>
            <div class="form-group Icon" >
                <label for="" class="control-label col-sm-3">身份类型 </label>
                <div class="col-sm-2">
                    <p  class="form-control" >{{empty($adminer->rname)?'超级管理员':$adminer->rname}}</p>
                </div>
            </div>

            <div class="form-group">
                <label for="" class="control-label col-sm-3">创建时间 </label>
                <div class="col-sm-3">
                    <p  class="form-control" >{{date('Y-m-d H:i:s',$adminer->created_at)}}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-sm-3">上次登录时间 </label>
                <div class="col-sm-3">
                    <p  class="form-control" >{{date('Y-m-d H:i:s',unserialize($adminer->history)[0])}}</p>
                </div>
            </div>
            <div class="form-group Icon" >
                <label for="" class="control-label col-sm-3">电话号码 </label>
                <div class="col-sm-3">
                    <p  class="form-control" >{{$adminer->phone}}</p>
                </div>
            </div>
            <div class="form-group Icon" >
                <label for="" class="control-label col-sm-3">邮箱 </label>
                <div class="col-sm-4">
                    <p  class="form-control" >{{$adminer->email}}</p>
                </div>
            </div>

            <div class="form-group Icon" >
                <label for="" class="control-label col-sm-3">登录次数 </label>
                <div class="col-sm-2">
                    <p  class="form-control" style="color: #478FCA;" ><b>{{$adminer->login_times}}</b></p>
                </div>
            </div>
            <div class="form-group">
                <label for="" class="control-label col-sm-3">密码修改 </label>
                <div class="col-sm-5">
                    <input type="button" class="btn btn-danger" value="修改密码" onclick="showUpView();" />
                </div>
            </div>
        </form>

    </div>
    <div id="update_form" style="display: none;">
        <br>
        <form class="form-horizontal" role="form" action="javascript:;" style="width: 95%;" >
            <div class="form-group has-warning">
                <label for="" class="control-label col-sm-4">原密码 </label>
                <div class="col-sm-8">
                    <input type="password" name="old_pwd"  class="form-control"  placeholder="原密码">
                </div>
            </div>
            <div class="form-group has-warning">
                <label for="" class="control-label col-sm-4">新密码 </label>
                <div class="col-sm-8">
                    <input type="password" name="password"  class="form-control"  placeholder="(以数字字母或?%&=_-.)6到20位">
                </div>
            </div>
            <div class="form-group has-warning">
                <label for="" class="control-label col-sm-4">确认密码 </label>
                <div class="col-sm-8">
                    <input type="password" name="password_confirmation"  class="form-control"  placeholder="确认密码">
                </div>
            </div>
        </form>
    </div>



@endsection

@section('js')

<script type="text/javascript">

    //修改密码
    function showUpView(){
        var url = "{{u('admin/setting/updateAdminPwd')}}";
        layui.use('layer',function(){
            layer.open({
                type:1,
                title:'修改密码',
                area: '360px',
                content:$('#update_form'),
                anim:3,
                btn:['确认','取消'],
                btn1:function(index,elem){
                    var data = $(elem).find('form').serialize();
                    $.post(url,data,function(res){
                        if(res.code > 0){
                            layer.msg(res.msg,{icon:2});
                        }else{
                            layer.msg('修改成功',{time:200},function(){
                                window.location.reload();
                            });

                        }
                    },'json')
                },
                btn2:function(index){
                    layer.close(index);
                }
            });
        });
    }

    //修改头像
    function upLoadPhotos(){
        $('#headfile').trigger('click');
    }
    $('#headfile').change(function(){
        var url = "{{u('admin/setting/modifyhi')}}";
        var formData = new FormData();
        formData.append("file", document.getElementById('headfile').files[0]);
        formData.append("compress", 200);
        layui.use(['layer'], function() {
            $.ajax({
                url: url,
                type: "post",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    layer.msg('上传中...', {icon: 16, time: 500});
                },
                success: function (res) {
                    if(res.code > 0){
                        layer.msg(res.msg,{icon:2});
                    }else{
                        $("#head_img").attr('src',res.data.src);
                    }
                }
            });
        });
    });

</script>
@endsection