@extends('admin.layouts.base')

@section('css')
    <link rel="stylesheet" href="{{ADMINUI}}/css/xenon-forms.css">
    {{--http://demo.cssmoban.com/cssthemes3/mstp_115_enonadmin/forms-native.html--}}
@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form action="{{u('admin/article/columnList')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="title"  class="form-control" size="15" placeholder='文章标题' value="{{$search['title'] ?? '' }}" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>评论列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <form action="javascript:;" method="post" class="searchform form-inline">
                    <div class="form-group">
                        评论间隔(分钟)：<input type="text" name="COMMENT_INTERVAL"  class="form-control" size="15"  value="{{$article[1]['content']}}" />
                    </div>

                    <div class="form-group">
                        每天评论次数：<input type="text" name="COMMENT_TIMES"  class="form-control" size="15"  value="{{$article[0]['content']}}" />
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary btn-danger btn-single" onclick="submitNdata(this);" data-url="{{u('admin/article/commentInterval')}}" data-jurl="{{u('admin/article/commentList')}}" >确认</button>
                    </div>

                </form>

            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>文章标题</th>
                    <th>用户IP</th>
                    <th>用户名</th>
                    <th>用户电话</th>
                    <th>用户邮箱</th>
                    <th>评论内容</th>
                    <th>评论时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$comment->isEmpty())
                    @foreach($comment as $item)
                    <tr>
                        <td>{{($item->pid != 0)?'&emsp;|--'.$item->cname:$item->cname}}</td>
                        <td>{{str_limit($item->seo_title,15,'...')}}</td>
                        <td>{{str_limit($item->seo_key,15,'...')}}</td>
                        <td>{{str_limit($item->seo_desc,15,'...')}}</td>
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
                                      <a  href="{{u('admin/article/addeColumn',['c_id' => $item->id])}}" >编辑栏目</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/article/delColumn')}}','c_id','{{$item->id}}');">删除栏目</a>
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
                {{ $comment->links() }}
            @else
                {{ $comment->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script>

</script>
@endsection