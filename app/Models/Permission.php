<?php
namespace App\Models;

class Permission extends Base{

//    protected $fillable = ['id','pname','pid','route','level','desc','font','sort'];

    /**
     * @函数描述  提交保存权限
     * @参数描述  array $data
     * @return  array
     * @Created on 2017/12/25 13:30
     * @Author: ZhangHao <247073050@qq.com>
     */
    public static function submitPermiss($data){

        $level = ($data['pid'] == 0)?1:(Permission::where('id',$data['pid'])->value('level') + 1);
        if(empty($data['id'])){
            $route = Permission::where('route',str_replace('.','/',$data['route']))->where('route','<>','admin/look')->first();
            if(!empty($route)){
                return ['code' => 999,'msg' => '此路由已存在'];
            }
            $permiss = new Permission();
        }else{
            $permiss = Permission::find($data['id']);
        }
        $permiss->pname = $data['pname'];
        $permiss->pid = $data['pid'];
        $permiss->route = str_replace('.','/',$data['route']);
        $permiss->level = $level;
        $permiss->desc = $data['desc'];
        $permiss->font = $data['font'];
        $permiss->sort = $data['sort'];
        $res = $permiss->save();
        if(empty($res)){
            return ['code' => 999,'msg' => '操作失败'];
        }
        return ['code' => 0,'msg' => ''];
    }


}