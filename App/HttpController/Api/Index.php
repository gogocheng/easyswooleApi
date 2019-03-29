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
use App\Model\Video as videoModel;


class Index extends Base
{
//    public function index ()
//    {
////        // TODO: Implement index() method.
//    }

    /**
     * description   首页分页列表
     * @throws \Exception
     */
    public function lists ()
    {
        $params = $this -> request() -> getRequestParam();
        //page 1
        //size 10
        //cat_id 1
        //count  查询
        //lists
        $videoModel = new videoModel();
        $videoModel -> getVideoData([],1,5);

    }

    /**
     * description  demo测试，json输出
     * @return bool
     */
    public function test ()
    {
        $data = [
            'ad' => 1,
            'cb' => 2,
            'params' => $this -> request() -> getRequestParam()
        ];
        return $this -> writeJson(200, 'success', $data);
    }

    /**
     * description   mysql  测试
     * @return bool
     */
    public function testMysql ()
    {
        $db = Di ::getInstance() -> get("MYSQL");
        $res = $db -> where('username', "admin") -> getOne("ck_user");
        return $this -> writeJson(200, 'success', $res);
    }

    /**
     * description  redis 测试
     * @return bool
     */
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

    /**
     * description  yaconf 测试
     * @return bool
     */
    public function yaconf ()
    {
        $res = \Yaconf ::get('redis');
        return $this -> writeJson(200, 'success', $res);
    }

    /**
     * description REDIS队列测试
     */
    public function pub ()
    {
        $params = $this -> request() -> getRequestParam();
        Di ::getInstance() -> get("REDIS") -> rPush("es_test_list", $params['num']);
//        return $this -> writeJson(200, 'success', $res);
    }
}