//上传图片
function uploadImg(obj,nVal){
    var url = $(obj).data('url');
    var durl = $(obj).data('durl');
    var delsrc = $(obj).data('del');
    var formData = new FormData();
     formData.append('file' ,obj.files[0]);
    layui.use(['layer'], function() {
        $.ajax({
            url: url,
            type: "post",
            data: formData,
            dataType:'json',
            contentType: false,
            processData: false,
            beforeSend: function () {
                layer.msg('上传中...', {icon: 16, time: 10000});
            },
            success: function (res) {
                if(res.code > 0){
                    layer.msg(res.msg,{icon:2});
                }else{
                    //判断是多图还是单图
                    if(nVal.indexOf('[]') >= 0 ){
                        //多图
                        var content = '<li><div class="pic_box">'
                            +'<img src="'+res.data.src+'">'
                            +'<input type="hidden" name='+nVal+' value='+res.data.src+'>'
                            +'<div class="delete_pic" onclick='+"delimg(this,'"+durl+"');"+'  >'
                            +'<img src='+delsrc+' ></div></div></li>';
                        $(obj).parents('.img-box').find('ul').append(content);
                    }else{

                        //单图
                        var content = '<li><div class="pic_box">'
                            +'<img src="'+res.data.src+'">'
                            +'<input type="hidden" name='+nVal+' value='+res.data.src+'>'
                            +'<div class="delete_pic" onclick='+"delimg(this,'"+durl+"');"+' >'
                            +'<img src='+delsrc+' ></div></div></li>';
                        $(obj).parents('.img-box').find('ul').html(content);
                    }

                }
            },
            error:function(xhr,ts,errorThrown){
                console.log(xhr);
            },
            complete:function(){
                obj.value = '';
                layer.closeAll();
            }
        });
    });
}

//删除图片
function  delimg(obj,url){
    var imgurl = $(obj).prev("input[type='hidden']").val();
    $.post(url,{imgurl:imgurl},function(res){
        layui.use('layer',function(){
            if(res.code > 0){
                layer.msg(res.msg,{icon:2});
            }else{
                $(obj).parents('li').remove();
            }
        })
    });
}
