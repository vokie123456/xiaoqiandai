@extends('admin.layouts.base')

@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/xenon-forms.css">
    {{--http://demo.cssmoban.com/cssthemes3/mstp_115_enonadmin/forms-native.html--}}
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form action="{{u('admin/article/manageList')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="title"  class="form-control" size="15" placeholder='文章标题' value="{{$search['title'] ?? '' }}" />
            </div>
            <span style="margin-left:1em;font-size: 14px !important;">所属栏目:</span>
            <div class="form-group">

                <select name="column_id"  class="form-control" >
                    <option value="0">全部栏目</option>
                    @if(!$column->isEmpty())
                        @foreach($column as $col)
                            <option value="{{$col->id}}"  {{(!empty($search['column_id']) && ( $search['column_id'] == $col->id)) ?'selected':''}}>{{$col->cname}}</option>
                            @if(!$col->column->isEmpty())
                                @foreach($col->column as $child_col)
                                    <option value="{{$child_col->id}}"  {{(!empty($search['column_id']) && ( $search['column_id'] == $child_col->id)) ?'selected':''}}>{{'&emsp;|--'.$child_col->cname}}</option>
                                 @endforeach
                            @endif
                        @endforeach
                    @endif
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
        <legend><b>文章列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <a href="{{u('admin/article/addeArticle')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>封面</th>
                    <th>标题</th>
                    <th>所属栏目</th>
                    <th>作者</th>
                    <th>点击数</th>
                    <th>收藏数</th>
                    <th>评论数</th>
                    <th>发布时间</th>
                    <th>创建时间</th>
                    <th>排序</th>
                    <th>审核状态</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$article->isEmpty())
                    @foreach($article as $item)
                    <tr>
                        <td><img src="{{$item->cover}}" style="max-width: 100px;" class="img-thumbnail"></td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->cname}}</td>
                        <td>{{$item->author}}</td>
                        <td>{{$item->hits}}</td>
                        <td>{{$item->collect}}</td>
                        <td>{{$item->comment_count}}</td>
                        <td>{{empty($item->publish_time)?'':date('Y-m-d',$item->publish_time)}}</td>
                        <td>{{date('Y-m-d',$item->created_at)}}</td>
                        <td>{{$item->sort}}</td>
                        <td>
                            <input class="iswitch iswitch-orange"  type="checkbox"  {{($item->status == 'on')?'checked':''}}
                            onclick="modifyStatus(this,'{{$item->id}}');"  data-status="{{$item->status}}" />
                        </td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/article/addeArticle',['a_id' => $item->id])}}" >编辑文章</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/article/delArticle')}}','a_id','{{$item->id}}');">删除文章</a>
                                  </li>
                              </ul>
                          </div>
                      </td>

                    </tr>
                   @endforeach
                @endif
                </tbody>
            </table>
            @if(empty($search))
                {{ $article->links() }}
            @else
                {{ $article->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{LAYDATE}}/laydate.js"></script>
<script>
    laydate.skin('molv');
    //修改状态
    function modifyStatus(obj,tar_id){
        var url = "{{u('admin/article/auditArticle')}}";
        var status = $(obj).attr('data-status');
        $.post(url,{tar_id:tar_id,status:status},function(res){
            layui.use(['layer'],function(){
                if(res.code > 0){
                    if(parseInt(status) == 'on'){
                        //原本是上架的,选中的
                        $(obj).prop('checked',true);
                    }else{
                        //原本下架的,未选中的
                        $(obj).prop('checked',false);
                    }
                    layer.msg(res.msg,{maxWidth:150,time:700});
                }else{
                    if(parseInt(status) == 'on'){
                        //原本是上架的,选中的
                        $(obj).attr('data-status',2);
                    }else{
                        //原本下架的,未选中的
                        $(obj).attr('data-status',1);
                    }
                    layer.msg('操作成功',{maxWidth:150,time:700});
                }
            })
        },'json');
    }
</script>
@endsection