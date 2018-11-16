<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/11/15
 * Time: 21:47
 */

namespace App\HttpController\Api;


use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Http\AbstractInterface\Controller;
use App\HttpController\Api\Base;
use App\Lib\Redis\Redis;

class Index extends Base
{
//    public function index ()
//    {
////        // TODO: Implement index() method.
//    }

    public function test ()
    {
        $data = [
            'ad' => 1,
            'cb' => 2,
            'params' => $this -> request() -> getRequestParam()
        ];
        return $this -> writeJson(200, 'success', $data);
    }

    public function testMysql ()
    {
        $db = Di ::getInstance() -> get("MYSQL");
        $res = $db -> where('username', "admin") -> getOne("ck_user");
        return $this -> writeJson(200, 'success', $res);
    }

    public function getRedis ()
    {
//        $redis = new \Redis();
//        $redis -> connect('127.0.0.1', 6379, 20);
//        $redis -> set('kk', 100);
//        $res = Redis ::getInstance() -> get("test");
        //注入后
        $res = Di ::getInstance() -> get("REDIS") -> get("test");
        return $this -> writeJson(200, 'success', $res);
    }
}