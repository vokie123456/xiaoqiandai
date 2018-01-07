@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/setting/webset')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="mold"  class="form-control" size="15" placeholder='类型' />
            </div>
            <div class="form-group">
                <input type="text" name="means"  class="form-control" size="15" placeholder='配置名' />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>标准列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <a href="{{u('admin/setting/addeConfig')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>配置名</th>
                    <th>类型</th>
                    <th style="max-width: 350px;">配置值</th>
                    <th>别名</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$config->isEmpty())
                    @foreach($config as $item)
                    <tr>
                        <td>{{$item->means}}</td>
                        <td>{{$item->mold}}</td>
                        <td style="max-width: 350px;overflow: hidden;">{{($item->show == 3)?'':$item->content}}</td>
                        <td>{{$item->name}}</td>
                      <td>{{$item->sort}}</td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/setting/addeConfig',['c_id' => $item->id])}}" >编辑配置</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/setting/delConfig')}}','c_id',{{$item->id}});">删除标准</a>
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
                {{ $config->links() }}
            @else
                {{ $config->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection