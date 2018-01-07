@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/appear/carousel')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="name" value="{{empty($search['name'])?'':$search['name']}}" class="form-control" size="15" placeholder="广告名" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>广告列表</b></legend>
        <div class="layui-field-box">
            <div class="form-group">
                <a href="{{u('admin/appear/addeAdvert')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>展示图</th>
                    <th>广告名</th>
                    <th>对应页面</th>
                    <th>页面位置</th>
                    <th>链接地址</th>
                    <th>状态</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$advert->isEmpty())
                    @foreach($advert as $item)
                    <tr>
                        <td ><img style="max-width:250px;" src="{{$item->show_img}}" title="{{$item->desc}}" class="img-thumbnail"></td>
                        <td>{{$item->name}}</td>
                        <td>{{config('appear.page')[$item->page]}}</td>
                        <td>{{config('appear.position')[$item->position]}}</td>
                        <th>{{$item->link_addr}}</th>
                        <th>@if($item->status == 1)
                                <span style="color: #0d957a;">正常</span>
                            @else
                                <span style="color: #dd1144;">禁用</span>
                            @endif
                        </th>
                        <td>{{$item->sort}}</td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/appear/addeAdvert',['adver_id' => $item->id])}}">编辑广告</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/appear/delAdvert')}}','adver_id','{{$item->id}}');">删除广告</a>
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
                {{ $advert->links() }}
            @else
                {{ $advert->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection