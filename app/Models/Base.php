<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/19
 * Time: 11:09
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Base extends Model{


    public $timestamps = false;

    protected $keyType = 'varchar';



    public function getTable(){

        return $this->table ? $this->table : strtolower(snake_case(class_basename($this)));
    }



    /**
     * @函数描述  修改上传图片状态
     * @参数描述 array|string $imgurl
     * @参数描述 int $status
     * @return  void
     * @Created on 2017/12/25 13:27
     * @Author: ZhangHao <247073050@qq.com>
     */
    protected function upStatusPic($imgurl,$status){
        $where = [];
        if(is_array($imgurl)){

            foreach ($imgurl as $url){

                $where[] = $url;

                if(strpos($url,'/cpre') !== false){

                    $where[] = str_replace('/cpre','/',$url);
                }
            }
        }else{

            $where[] = $imgurl;

            if(strpos($imgurl,'/cpre') !== false){

                $where[] = str_replace('/cpre','/',$imgurl);
            }
        }
        HistoryImg::whereIn('src',$where)->update(['status' => $status]);
    }

    /**
     * @函数描述  匹配img标签,返回所有的img的src数组集合
     * @参数描述  string $str
     * @return  array
     * @Created on 2017/12/25 13:28
     * @Author: ZhangHao <247073050@qq.com>
     */
    protected function getAllImgSrc($str){

        $pattern = '/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/';

        $match = [];

        preg_match_all($pattern, $str, $match);

        return $match[1];
    }


}
