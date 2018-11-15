<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/11/15
 * Time: 21:47
 */

namespace App\HttpController\Api;


use EasySwoole\Core\Http\AbstractInterface\Controller;
use App\HttpController\Api\Base;

class Index extends Base
{
//    public function index ()
//    {
////        // TODO: Implement index() method.
//    }

    public function test(){
        new a();
        $data = [
            'ad' => 1,
            'cb' => 2,
            'params'=> $this->request()->getRequestParam()
        ];
        return $this -> writeJson(200, 'success', $data);
    }
}