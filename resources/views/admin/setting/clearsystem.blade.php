@extends('admin.layouts.base')
@section('css')
   <link rel="stylesheet" href="{{ADMINUI}}/css/upimg.css">
@endsection
@section('content')
<div style="margin: 15px;">

    <div class="layui-tab layui-tab-brief" lay-filter="himg" lay-showPercent="true" >

        <ul class="layui-tab-title">
            <li class="layui-this">废弃图片</li>
            <li>缓存清理</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <div style="margin:1% .8%">

                    <button type="button" class="btn btn-danger" onclick="deleteHimg();"><i class="layui-icon">&#xe640;</i>全部删除</button>
                </div>
                <form action="javascript" id="himg_form">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <!-- upimg-start -->
                            <div class="img-box">
                                <div class="pic_show">
                                    <ul>
                                        @if(!$himg->isEmpty())
                                            @foreach($himg as $img)
                                            <li>
                                                <div class="pic_box">
                                                    <input type="hidden" name="himg_id[]" value="{{$img->id}}">
                                                    <img src="{{$img->src}}">
                                                    <input type="hidden"  value="{{$img->src}}">
                                                    <div class="delete_pic" onclick="delimg(this,'{{u('admin/setting/delpicture')}}');" >
                                                        <img src="{{ADMINUI}}/images/delete2.png" />
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <!-- upimg-end -->
                        </div>
                    </div>
                </form>

            </div>
            <div class="layui-tab-item">
                <button type="button"  class="btn btn-default" onclick="clearCache('cache');" ><i class="layui-icon">&#xe64d;</i>清理缓存</button>
                <button type="button"  class="btn btn-danger" onclick="clearCache('log');" ><i class="layui-icon">&#xe64d;</i>清理日志</button>
                <button type="button"  class="btn btn-info" onclick="clearCache('session');" ><i class="layui-icon">&#xe64d;</i>清理Session</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    <script type="text/javascript" src="{{ADMINUI}}/js/upimg.js"></script>
<script>
    layui.use(['element']);

    //删除
    function deleteHimg(){
        var data = $('#himg_form').serialize();
        var url = "{{u('admin/setting/deleteHimg')}}";
        $.post(url,data,function(res){
            layui.use(['layer'],function(){
                if(res.code > 0){
                    layer.msg(res.msg,{maxWidth:260,icon:2,anim:6});
                }else{
                    location.href = "{{u('admin/setting/clearSystem')}}";
                }
            });

        },'json');
    }

    //清理缓存
    function clearCache(type){
        var url = "{{u('admin/setting/clearCache')}}";
        layui.use('layer', function(){
            $.ajax({
                url: url,
                type: "post",
                data: {type:type},
                dataType:'json',
                beforeSend: function () {
                    layer.msg('清除中...',{time:1000,icon:16,shade:0.3,maxWidth:260});
                },
                success:function(res){
                    if(res.code > 0){
                        layer.msg(res.msg,{maxWidth:260,icon:2,anim:6});
                    }else{
                        layer.msg('清除完毕',{maxWidth:260,icon:1,anim:5});
                    }
                }
            });



        });
    }
</script>
@endsection

