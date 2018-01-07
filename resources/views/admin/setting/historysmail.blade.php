@extends('admin.layouts.base')
@section('css')

@endsection
@section('content')
<div style="margin: 15px;">
    <div style="margin-bottom: 1em;">
        <a href="{{u('admin/setting/emailSetting')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </div>
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/setting/historySMail')}}" method="post" class="searchform form-inline">
            <span style="margin-left:1em;font-size: 14px !important;">发送结果:</span>
            <div class="form-group">

                <select name="status"  class="form-control" >
                    <option value="0" {{empty($search['status'])?'checked':''}}> 全部</option>
                    <option value="success" {{(!empty($search['status']) && ($search['status'] == 'success'))?'selected':''}}>成功</option>
                    <option value="failed" {{(!empty($search['status']) && ($search['status'] == 'failed'))?'selected':''}}>失败</option>
                </select>
            </div>

            <div class="form-group">
                <input type="text" placeholder="开始时间" name="start_time" value="{{empty($search['start_time'])?'':$search['start_time']}}" onclick="laydate({istime: true,istoday: true, format: 'YYYY-MM-DD hh:mm:ss'})" class="form-control" size="25">
            </div>
            <div class="form-group">
                <input type="text" placeholder="结束时间" name="end_time" value="{{empty($search['end_time'])?'':$search['end_time']}}" onclick="laydate({istime: true,istoday: true, format: 'YYYY-MM-DD hh:mm:ss'})" class="form-control" size="25">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>发邮记录</b></legend>

        <div class="layui-field-box">

            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>发件主题</th>
                    <th>收件人集合</th>
                    <th>是否有附件</th>
                    <th>发送时间</th>
                    <th>发送者</th>
                    <th>发送状态</th>
                </tr>
                </thead>
                <tbody>
                @if(!$his_email->isEmpty())
                    @foreach($his_email as $item)
                    <tr>
                        <td>{{$item->theme}}</td>
                        <td>{{str_limit($item->touser,30,'...')}}</td>
                        <td>{{empty($item->enclosure)?'无':'含有'}}</td>
                        <td>{{date('Y-m-d H:i:s',$item->created_at)}}</td>
                        <td>{{$item->a_name}}</td>
                        <td @if($item->status == 'success') style="color:#35833d;" @else  style="color:#931326;" @endif><b>{{$item->status}}</b></td>
                    </tr>
                   @endforeach
                @endif
                </tbody>
            </table>
            @if(empty($search))
                {{ $his_email->links() }}
            @else
                {{ $his_email->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection
@section('js')
    <script type="text/javascript" src="{{LAYDATE}}/laydate.js"></script>
    <script>
        laydate.skin('molv');
    </script>
@endsection