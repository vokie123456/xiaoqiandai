@extends('admin.layouts.base')

@section('css')


    <link rel="stylesheet" href="{{WEDITOR}}/dist/css/wangEditor.min.css">

@endsection

@section('content')

    <form action="javascript:;">

        <div class="form-group">
            <label for="" class="control-label col-sm-2">《百福汇使用手册》 </label>
            <div class="col-sm-10">
                <textarea id="introduce" name="usemanual" style="min-height: 500px;" class="WANGEDITOR">{!! empty($config->content)?'':$config->content !!}</textarea>
            </div>
        </div>
        <div class="modal-footer" style="float:left; margin-left:45%;">
            <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/appear/usemanualpost')}}" data-jurl="{{u('admin/appear/usemanual')}}">确认</button>
        </div>
    </form>

@endsection

@section('js')
    <script type="text/javascript" src="{{WEDITOR}}/dist/js/wangEditor.min.js"></script>
<script type="text/javascript">
    $('.WANGEDITOR').each(function(){
        var id = $(this).attr('id');
        var editor = new wangEditor(id);
        // 上传图片（举例）
        editor.config.uploadImgUrl = "{{u('admin/setting/upload')}}";

        // 配置自定义参数（举例）
        editor.config.uploadParams = {
            //上传方式
            utype: 'weditor'

        };
        editor.config.uploadImgFileName = 'file';
        editor.config.pasteFilter = false;
        editor.config.printLog = false;

        editor.create();
    });

</script>
@endsection