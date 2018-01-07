<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 21:56
 */

namespace App\Http\Controllers\Admin;

use App\Models\Adminer;
use App\Models\Permission;
use App\Models\Role;
use Validator;
class PermissionController extends AuthController
{


    /**
     * @函数描述  管理员列表
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 10:55
     * @Author: ZhangHao <247073050@qq.com>
     */
   public function adminerList(){
     $args = $this->params;
     $data['search'] = $args;
     $adminer =  Adminer::select('adminer.*','role.rname')->join('role',function($join){
         $join->on('adminer.role_id','=','role.id');
     })->where('adminer.is_super',0);
     if(!empty($args['name'])){
         $adminer->where('adminer.name','like','%'.$args['name'].'%');
     }
     if(!empty($args['account'])){
           $adminer->where('adminer.account','like','%'.$args['account'].'%');
      }
     $data['adminer'] = $adminer->paginate(10);
     return view($this->vname,$data);
   }

    /**
     * @函数描述  管理员信息编辑
     * @参数描述 array $this->params
     * @return HTML
     * @Created on 2017/12/25 10:56
     * @Author: ZhangHao <247073050@qq.com>
     */
   public function addeAdminer(){
       $args = $this->params;
       if(!empty($args['adminer_id'])){
         $data['adminer'] =  Adminer::where('id',$args['adminer_id'])->first();
       }
       $data['role'] = Role::orderBy('created_at','asc')->get();
       return view($this->vname,$data);
   }

    /**
     * @函数描述  提交保存管理员编辑信息
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 10:57
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveAdata(){
       $args = $this->params;
       $validator = Validator::make($args,[
           'name' => 'required|max:20',
           'role_id' => 'required',
           'account' => 'required|max:20',
           'password' => 'required|regex:/^[\w\?%&=\-_\.]{6,20}$/',
           'head_img' => 'required',
           'phone' => 'nullable|regex:/^1[34578][0-9]{9}$/',
           'email' => 'nullable|email',

       ],[
           'name.required' => '管理员名不能为空',
           'name.max' => '管理员名过长',
           'role_id.required' => '所属的管理角色不能为空',
           'account.required' => '账号不能为空',
           'account.max' => '账号过长',
           'password.required' => '密码不能为空',
           'password.regex' => '密码格式不对',

           'head_img.required' => '头像不能为空',
           'phone.regex' => '电话格式不对',
           'email.email' => '邮箱格式不对',
       ]);

       if($validator->fails()){
           return ['code' => 999,'msg' =>$validator->errors()->first() ];
       }
       if(!empty($args['adminer_id'])){
           $adminer = Adminer::find($args['adminer_id']);
           //把以前的头像改为废弃的,并生效目前上传的
           if($adminer->head_img != $args['head_img']){
               $this->upStatusPic($adminer->head_img,0);
               $this->upStatusPic($args['head_img'],1);
           }
       }else{
           $adminer = new Adminer;
           //让上传的图片生效
           $this->upStatusPic($args['head_img'],1);
       }
        $adminer->name = $args['name'];
        $adminer->role_id = $args['role_id'];
        $adminer->account = $args['account'];
        $adminer->password = encrypt($args['password']);
        $adminer->head_img =  $args['head_img'];
        $adminer->phone =  $args['phone'];
        $adminer->email =  $args['email'];
        $adminer->created_at =  time();
        $adminer->is_super =  0;
        $res = $adminer->save();
        if(empty($res)){
            return ['code' => 999,'msg' => '信息没有修改的'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述  删除管理员
     * @参数描述 array $this->params
     * @return  array
     * @Created on 2017/12/25 10:58
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delAdminer(){
        $args = $this->params;
        $adminer = Adminer::find($args['adminer_id']);
        if($adminer->head_img != '/adminui/images/default.png'){
           @unlink(base_path('public_html'.$adminer->head_img));
        }
        $res = $adminer->delete();
        if(empty($res)){
            return ['code' => 999,'msg' => '删除失败'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述 角色列表
     * @参数描述 array $this->params
     * @return HTML
     * @Created on 2017/12/25 10:59
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function  roleList(){
      $args = $this->params;
      $data['search'] = $args;
      $role  = Role::orderBy('created_at','asc');
      if(!empty($args['rname'])){
          $role->where('rname','like','%'.$args['rname'].'%');
      }
      $data['role'] = $role->paginate(5);
      return view($this->vname,$data);
    }

    /**
     * @函数描述 添加-编辑角色信息
     * @参数描述 array $this->params
     * @return array
     * @Created on 2017/12/25 10:59
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function addeRole(){
        $args = $this->params;
        $permids = [];
        if(!empty($args['role_id'])){
            $data['role'] = Role::where('id',$args['role_id'])->first();
            $permids = unserialize($data['role']->permiss_ids);
        }
        $permiss = Permission::orderBy('level','asc')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();

        $tree = $this->getTree($permiss,0,$permids);
        $data['nodes'] = $tree;
        return view($this->vname,$data);
    }


    /**
     * @函数描述  拼装前端菜单的树形结构
     * @参数描述  array $permiss  所有路由
     * @参数描述  int $pid        父级路由ID
     * @参数描述  array $permids  需要选中的路由ID
     * @return  array
     * @Created on 2017/12/25 11:00
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function getTree($permiss ,$pid,$permids){
        $result = [];
        foreach ($permiss as $perm){
            if($perm['pid'] == $pid){
                $child =  ['name' => $perm['pname'],'spread' => false,'checked' => false , 'target'=>'_self','checkboxValue'=>$perm['id'] ];
                if(!empty($permids)){
                    if(in_array($perm['id'],$permids)){
                        $child['checked'] = true;
                    }
                }
                $child['children'] = $this->getTree($permiss,$perm['id'],$permids);
                if(empty($child['children'])){
                    unset($child['children']);
                }
                array_push($result,$child);
            }
        }

        return $result;
    }

    /**
     * @函数描述  提交角色表单信息
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:03
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function saveRinfo(){
        $args = $this->params;
        if(empty($args['permiss_ids'])){
            return ['code' => 999,'msg' => '权限不能选择为空'];
        }
        if(!empty($args['role_id'])){
            $role = Role::find($args['role_id']);
        }else{
            $role = new Role;
        }
        $role->rname = $args['rname'];
        $role->created_at = time();
        $role->permiss_ids = serialize($args['permiss_ids']);
        $res = $role->save();
        if(empty($res)){
            return ['code' => 999,'msg' => '角色信息并未修改'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述  删除角色
     * @参数描述 array $this->params
     * @return  array
     * @Created on 2017/12/25 11:04
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function deltoRole(){
        $args = $this->params;
        $adminer = Adminer::where('role_id',$args['role_id'])->get();
        if(!$adminer->isEmpty()){
            return ['code' => 999,'msg' => '有下属管理员,无法直接删除'];
        }
        $res = Role::where('id',$args['role_id'])->delete();
        if(empty($res)){
            return ['code' => 999,'msg' => '角色删除失败'];
        }
        return ['code' => 0,'msg' => ''];
    }

    /**
     * @函数描述  权限列表
     * @参数描述  array $this->params
     * @return  HTML
     * @Created on 2017/12/25 11:05
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function permissionList(){
        $args = $this->params;
        $data['search'] = $args;
        $permission = Permission::select('permission.*','pperm.pname as ppname')
           ->leftJoin('permission AS pperm',function($join){
           $join->on('permission.pid','=','pperm.id');
         });
        if(!empty($args['pname'])){
            $permission->where('permission.pname','like','%'.$args['pname'].'%');
        }
        if(!empty($args['cpname'])){
           $perm = Permission::where('pname','like','%'.$args['cpname'].'%')->first();
           if(!empty($perm)){
               $id_pid = Permission::select(['id','pid'])->get()->toArray();
               $accord = [];
               $fn = function($id_pid,$pid,&$accord) use(&$fn){
                   foreach ($id_pid as $ids){
                       if($ids['pid'] == $pid){
                           array_push($accord,$ids['id']);
                           $fn($id_pid,$ids['id'],$accord);
                       }
                   }
               };
               $fn($id_pid,$perm->id,$accord);
               array_unshift($accord,$perm->id);
               $permission->whereIn('permission.id',$accord);
           }
        }
       $data['permission'] =  $permission->orderBy('permission.pid','asc')
                                         ->orderBy('permission.sort','asc')
                                         ->paginate(10);
       $res_permiss = Permission::where('level','<=',2)->orderBy('pid','asc')->orderBy('sort','asc')->orderBy('id','asc')->get()->toArray();
       $tree_perm = [];
       foreach ($res_permiss as $key=>$pper){
           if($pper['pid'] == 0){
             array_push($tree_perm,$pper);
             unset($res_permiss[$key]);
             foreach ($res_permiss as $value){
                 if($value['pid'] == $pper['id']){
                     $value['pname'] = '&emsp;|__'.$value['pname'];
                     array_push($tree_perm,$value);
                 }
             }
           }
       }
        array_unshift($tree_perm,['id' => 0,'pname' => '顶级权限','level' => 0]);
        $data['tree_perm'] = $tree_perm;
       return view($this->vname,$data);
    }

    /**
     * @函数描述  提交权限信息
     * @参数描述  array $this->params
     * @return  array
     * @Created on 2017/12/25 11:05
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function submitPermiss(){
        $validator = Validator::make($this->params,[
            'pname' => 'required',
            'route' => 'required',
        ],[
            'pname.required' => '权限名不能为空',
            'route.required' => '权限路由不能为空',
        ]);
        if($validator->fails()){
            return ['code' => 999,'msg' => $validator->errors()->first()];
        }

       return  Permission::submitPermiss($this->params);
    }


    /**
     * @函数描述 删除权限
     * @参数描述 array $this->params
     * @return array
     * @Created on 2017/12/25 11:06
     * @Author: ZhangHao <247073050@qq.com>
     */
    public function delPermission(){
        $args = $this->params;
        $perm = Permission::find($args['perm_id']);
        if(empty($perm)){
            return ['code' => 999,'msg' => '没有此权限'];
        }
        $res = $perm->delete();
        if(empty($res)){
            return ['code' => 999,'msg' => '删除失败'];
        }
        return ['code' => 0,'msg' => ''];
    }
}