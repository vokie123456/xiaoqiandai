@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/appear/agreement')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="name" value="{{empty($search['name'])?'':$search['name']}}" class="form-control" size="15" placeholder="协议名" />
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>协议列表</b></legend>
        <div class="layui-field-box">
            <div class="form-group">
                <a href="{{u('admin/appear/addeAgreement')}}"><button  class="btn  btn-info"> <i class="layui-icon">&#xe654;</i>添加</button></a>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>协议名</th>
                    <th>别名</th>
                    <th>类型</th>
                    <th>链接地址</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$agreement->isEmpty())
                    @foreach($agreement as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->name}}</td>
                        <td>{{$item->alias}}</td>
                        <td>{{config('appear.agree_type')[$item->agree_type]}}</td>
                        <td>{{$item->link_addr}}</td>
                        <td>{{$item->sort}}</td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a  href="{{u('admin/appear/addeAgreement',['agr_id' => $item->id])}}">编辑协议</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/appear/delAgreement')}}','agr_id',{{$item->id}});">删除协议</a>
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
                {{ $agreement->links() }}
            @else
                {{ $agreement->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>
@endsection