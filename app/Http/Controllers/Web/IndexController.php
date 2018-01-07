<?php
/**
 * Created by PhpStorm.
 * User: Zhang
 * Date: 2017/12/19
 * Time: 11:24
 */

namespace App\Http\Controllers\Web;


class IndexController extends BaseController{


    public function index(){

        return $this->view();

    }

}