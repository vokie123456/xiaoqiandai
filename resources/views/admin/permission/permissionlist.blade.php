@extends('admin.layouts.base')

@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <form role="form" action="{{u('admin/permission/permissionList')}}" method="post" class="searchform form-inline">
            <div class="form-group">
                <input type="text" name="pname"  class="form-control" value="{{empty($search['pname'])?'':$search['pname']}}" size="15" placeholder="权限名" />
            </div>
            <div class="form-group">
                <input type="text" name="cpname"  class="form-control" value="{{empty($search['cpname'])?'':$search['cpname']}}" size="15" placeholder="权限名(含下级)" />
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-secondary btn-single">搜索</button>
            </div>

        </form>
    </blockquote>

    <fieldset class="layui-elem-field">
        <legend><b>权限列表</b></legend>

        <div class="layui-field-box">

            <div class="form-group">
                <button type="submit" class="btn  btn-info" data-toggle="modal" data-target="#myModal"> <i class="layui-icon">&#xe654;</i>添加</button>
            </div>
            <table class="layui-table" lay-skin="nob">
                <thead>
                <tr>
                    <th>序号</th>
                    <th>图标</th>
                    <th>权限名</th>
                    <th>上级名</th>
                    <th>权限路由</th>
                    <th>权限level</th>
                    <th>描述</th>
                    <th>排序</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody>
                @if(!$permission->isEmpty())
                    @foreach($permission as $item)
                    <tr>
                      <td>{{$item->id}}</td>
                      <td><i class="layui-icon">{{$item->font}}</i></td>
                      <td>{{$item->pname}}</td>
                      <td>{{empty($item->ppname)?'顶级权限':$item->ppname}}</td>
                      <td>{{$item->route}}</td>
                      <td>{{$item->level}}</td>
                      <td>{{$item->desc}}</td>
                      <td>{{$item->sort}}</td>
                      <td>
                          <div class="btn-group">
                              <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                  操作 <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown" style="min-width: 110px;" role="menu">
                                  <li>
                                      <a href="javascript:;" data-toggle="modal" data-target="#myModal{{$item->id}}"  >编辑权限</a>
                                  </li>
                                  <li class="divider"></li>
                                  <li>
                                      <a href="javascript:;" onclick="getpdel('{{u('admin/permission/delPermission')}}','perm_id',{{$item->id}});">删除权限</a>
                                  </li>

                              </ul>
                          </div>
                          <!-- Modal edit-->
                          <div class="modal fade" id="myModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                                  <div class="modal-content">
                                      <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                          <h4 class="modal-title" id="myModalLabel"><b>编辑权限</b></h4>
                                      </div>
                                      <div class="modal-body">
                                          <form class="form-horizontal" role="form" action="javascript:;">
                                              <input type="hidden" name="id" value="{{$item->id}}" >
                                              <div class="form-group">
                                                  <label for="" class="control-label col-sm-3">权限名 </label>
                                                  <div class="col-sm-5">
                                                      <input type="text" name="pname" value="{{$item->pname}}" class="form-control"  placeholder="权限名">
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="" class="control-label col-sm-3">父级权限 </label>
                                                  <div class="col-sm-5">
                                                      <select name="pid" class="form-control selectIcon" >
                                                          @foreach($tree_perm as $perm)
                                                              <option value="{{$perm['id']}}" data-level="{{$perm['level']}}" {{($perm['id'] == $item['pid'])?'selected':''}}>{{$perm['pname']}}</option>
                                                          @endforeach
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="" class="control-label col-sm-3">路由 </label>
                                                  <div class="col-sm-5">
                                                      <input type="text" name="route" value="{{$item->route}}" class="form-control"  placeholder="路由">
                                                  </div>
                                              </div>
                                              <div class="form-group Icon" @if($item->level == 3) style="display: none;" @endif>
                                                  <label for="" class="control-label col-sm-3">图标 </label>
                                                  <div class="col-sm-3">
                                                      <input type="text" name="font" value="{{empty($item->font)?'':'&amp;'}}{{substr($item->font,1)}}" class="form-control"  placeholder="图标">
                                                  </div>
                                                  <div class="col-sm-2" style="padding-left: 0px;">
                                                      <button class="btn  btn-warning" onclick="onloadIcon();" ><span class="glyphicon glyphicon-hand-up" ></span>选择</button>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="" class="control-label col-sm-3">描述 </label>
                                                  <div class="col-sm-5">
                                                      <input type="text" name="desc" value="{{$item->desc}}" class="form-control"  placeholder="描述">
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="" class="control-label col-sm-3">排序 </label>
                                                  <div class="col-sm-5">
                                                      <input type="text" name="sort"  value="{{$item->sort}}" class="form-control"  placeholder="排序">
                                                  </div>
                                              </div>
                                          </form>
                                      </div>
                                      <div class="modal-footer">
                                          <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                          <button type="button" class="btn btn-primary" onclick="submitFdata(this);" data-url="{{u('admin/permission/submitPermiss')}}">确认</button>
                                      </div>
                                  </div>
                              </div>
                          </div>

                      </td>

                    </tr>
                   @endforeach
                @endif
                </tbody>
            </table>
            @if(empty($search))
                {{ $permission->links() }}
                @else
                {{ $permission->appends($search)->links() }}
            @endif
        </div>
    </fieldset>
</div>


<!-- Modal add-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" action="javascript:;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><b>添加权限</b></h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="javascript:;" role="form">
                    <input type="hidden" name="id" value="">
                    <div class="form-group">
                        <label for="" class="control-label col-sm-3">权限名 </label>
                        <div class="col-sm-5">
                            <input type="text" name="pname" class="form-control"  placeholder="权限名">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-3">父级权限 </label>
                        <div class="col-sm-5">
                            <select name="pid" class="form-control selectIcon" >
                                @foreach($tree_perm as $perm)
                                 <option value="{{$perm['id']}}" data-level="{{$perm['level']}}">{{$perm['pname']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-3">路由 </label>
                        <div class="col-sm-5">
                         <input type="text" name="route" class="form-control"  placeholder="路由">
                        </div>
                    </div>
                    <div class="form-group Icon">
                        <label for="" class="control-label col-sm-3">图标 </label>
                        <div class="col-sm-3">
                            <input type="text" name="font" class="form-control"  placeholder="图标">
                        </div>
                        <div class="col-sm-2" style="padding-left: 0px;">
                            <button class="btn  btn-warning" onclick="onloadIcon();" ><span class="glyphicon glyphicon-hand-up" ></span>选择</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-3">描述 </label>
                        <div class="col-sm-5">
                            <input type="text" name="desc" class="form-control"  placeholder="描述">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label col-sm-3">排序 </label>
                        <div class="col-sm-5">
                            <input type="text" name="sort" value="100" class="form-control"  placeholder="排序">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="submitFdata(this);" data-url="{{u('admin/permission/submitPermiss')}}">确认</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script>
  $('.selectIcon').change(function(){
      var level = parseInt($(this).find('option:selected').data('level'));
      if(level > 1){
          $(this).parents('form').find('.Icon').hide();
      }else{
          $(this).parents('form').find('.Icon').show();
      }
  });
  function onloadIcon(){
     window.open('http://www.layui.com/doc/element/icon.html');
  }



</script>
@endsection