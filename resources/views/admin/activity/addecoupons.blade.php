@extends('admin.layouts.base')
@section('css')


@endsection
@section('content')
<div style="margin: 15px;">
    <blockquote class="layui-elem-quote">
        <a href="{{u('admin/activity/coupons')}}">
            <button class="btn btn-default" style="color: #1AA094;">
                <span class="glyphicon glyphicon-share-alt" ></span>
                返回
            </button>
        </a>
    </blockquote>
    <fieldset class="layui-elem-field">
        <legend><b>{{empty($coupons)?'添加':'编辑'}}优惠券</b></legend>
        <div class="layui-field-box">
            <form class="form-horizontal" role="form">
                <input type="hidden" name="cp_id" value="{{empty($coupons->id)?'':$coupons->id}}" />
                <div class="form-group has-warning">
                    <label for="" class="control-label col-sm-2">优惠券名 </label>
                    <div class="col-sm-4">
                        <input type="text" name="name" value="{{empty($coupons->name)?'':$coupons->name}}" class="form-control"  placeholder="优惠券名">
                    </div>
                </div>
                <div class="form-group has-warning">
                    <label for="" class="control-label col-sm-2">优惠券描述 </label>
                    <div class="col-sm-4">
                        <textarea  name="describe" cols="5" rows="5" class="form-control"  placeholder="优惠券描述">{!! empty($coupons->describe)?'':$coupons->describe !!}</textarea>
                    </div>
                </div>
                <div class="form-group has-success">
                    <label for="" class="control-label col-sm-2">有效起始时间 </label>
                    <div class="col-sm-4">
                        <input type="text" name="valid_start" value="{{empty($coupons->valid_start)?'':date('Y-m-d H:i:s',$coupons->valid_start)}}" class="form-control" onclick="laydate({istime: true,istoday: true, format: 'YYYY-MM-DD hh:mm:ss'})"  readonly placeholder="起始有效时间">
                    </div>
                </div>
                <div class="form-group has-success">
                    <label for="" class="control-label col-sm-2">有效截止时间 </label>
                    <div class="col-sm-4">
                        <input type="text" name="valid_end" value="{{empty($coupons->valid_end)?'':date('Y-m-d H:i:s',$coupons->valid_end)}}" class="form-control" onclick="laydate({istime: true,istoday: true, format: 'YYYY-MM-DD hh:mm:ss'})"  readonly placeholder="结束有效时间">
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="" class="control-label col-sm-2">满足条件最低金额 </label>
                    <div class="col-sm-4">
                        <input type="text" name="satisfy_fee" value="{{empty($coupons->satisfy_fee)?'':$coupons->satisfy_fee}}" class="form-control"   placeholder="满足条件最低金额">
                    </div>
                </div>
                <div class="form-group has-error">
                    <label for="" class="control-label col-sm-2">优惠金额 </label>
                    <div class="col-sm-4">
                        <input type="text" name="reduce_fee" value="{{empty($coupons->reduce_fee)?'':$coupons->reduce_fee}}" class="form-control"  placeholder="优惠金额">
                    </div>
                </div>
                @if(empty($coupons->grant_count))
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">发放张数 </label>
                        <div class="col-sm-4">
                            <input type="text" name="grant_count"  class="form-control"  placeholder="发放张数">
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label for="" class="control-label col-sm-2">增加发放张数 </label>
                        <div class="col-sm-4">
                            <input type="text" name="add_grant_count"  class="form-control" value="0"  />
                        </div>
                        <div class="col-sm-3" >
                            <span style="color: #d62728; border: none !important;"  class="form-control">
                                 (目前剩余张数：{{$coupons->surplus_count}} )
                            </span>

                        </div>
                    </div>
                @endif


                <div class="form-group">
                    <label for="" class="control-label col-sm-2">排序 </label>
                    <div class="col-sm-4">
                        <input type="text" name="sort" class="form-control" value="{{empty($coupons->sort)?100:$coupons->sort}}" />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="submitNdata(this);" data-url="{{u('admin/activity/saveCoupons')}}" data-jurl="{{u('admin/appear/coupons')}}">确认</button>
                </div>
            </form>

        </div>
    </fieldset>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{LAYDATE}}/laydate.js"></script>
<script type="text/javascript" >
    laydate.skin('molv');
</script>
@endsection