<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/19
 * Time: 11:04
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\HistoryImg;
use View;

class BaseController extends Controller{


    protected $alias = '';
    protected $vname = '';
    protected $params = '';
    protected $request = null;

    function __construct(Request $request){
        $this->request = $request;
        $this->alias = Route::currentRouteName();
        $this->vname = strtolower(Route::currentRouteName());
        $this->params = $request->all();
    }

    //模板输出
    public function view($view = null, $data = [], $mergeData = []){

        $view = $view ?? $this->vname;

        return View::make($view,$data,$mergeData);
    }

    //修改上传图片状态
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
    //匹配img标签,返回所有的img的src数组集合
    protected function getAllImgSrc($str){
        $pattern = '/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/';
        $match = [];
        preg_match_all($pattern, $str, $match);
        return $match[1];
    }

    //富文本图片加上前缀
    public function prefixImgs($str){

        $pattern = '/<img[\s\S]*?src\s*=\s*[\"|\'](.*?)[\"|\'][\s\S]*?>/';
        return preg_replace($pattern,'<img src="'.substr(BASE_URL,0,-1).'${1}" style="max-width:100%" />',$str);
    }


}