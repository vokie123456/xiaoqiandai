@extends('admin.layouts.base')

@section('title','文件管理')

@section('content')
    <div style="margin: 15px;padding-top: 20px;">
        <div class="layui-tab layui-tab-brief" lay-filter="himg" lay-showPercent="true" >

        <ul class="layui-tab-title">
            <li class="layui-this">历史无效文件</li>
        </ul>
            <div class="layui-tab-content">
                <div style="margin:1% .8%">
                    <button type="button"  class="btn btn-default" onclick="delAllFiles(this);" ><i class="layui-icon">&#xe64d;</i>全部清理</button>
                </div>

                <form action="javascript:;" id="file_form">
                    @if(!$history_file->isEmpty())
                        @foreach($history_file as $file)

                        <span style="margin: 1em;border: 1px solid #c8a485;border-radius: 5px;padding: 5px; float:left;" class="fileshow" >
                            <label for="">{{date('Y-m-d H:i:s')}}</label>
                           <input type="hidden" name="file_ids[]" value="{{$file->id}}">
                            <i class="layui-icon">&#xe61d;</i>
                            <span>{{$file->org_name}}</span>&nbsp;
                            <span onclick="delInvalidFile(this);"><i class="layui-icon" style="color: #a02703;">&#x1007;</i></span>
                        </span>
                        @endforeach
                    @endif

                </form>

        </div>
        </div>
    </div>
@endsection
@section('js')
<script>
    function delAllFiles(){

        var data = $('#file_form').serialize();

        delInvalidFile('',data);
    }

    function delInvalidFile(obj,data){

        var url = "{{u('admin/setting/delInvalidFile')}}";
        if(!data){

            data = {file_ids:[$(obj).parents('.fileshow').find('input[name="file_ids[]"]').val()]};
        }

        $.post(url,data,function(res){
            layui.use(['layer'],function(){
                if(res.code > 0){
                    layer.msg(res.msg,{maxWidth:260});
                }else{
                    layer.msg('操作成功',{maxWidth:260,time:500},function(){
                       location.reload();
                    });
                }
            })
        },'json');

    }
</script>
@endsection