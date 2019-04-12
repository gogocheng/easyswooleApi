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
use EasySwoole\Core\Http\Message\Status;


class Index extends Base
{
//    public function index ()
//    {
////        // TODO: Implement index() method.
//    }

    /**
     * description   首页分页列表  第一套:原始方案，读取mysql数据
     * @throws \Exception
     */
    public function lists0 ()
    {
//        $params = $this -> request() -> getRequestParam();
//        $page = !empty($params['page']) ? intval($params['page']) : 1;
//        $size = !empty($params['size']) ? intval($params['size']) : 5;
        $condition = [];
        if (!empty($this -> params['cat_id'])) {
            $condition['cat_id'] = intval($this -> params['cat_id']);
        }
        //page 1
        //size 10
        //cat_id 1
        //count  查询
        //lists
        try {
            $videoModel = new videoModel();
            $data = $videoModel -> getVideoData($condition, $this -> params['page'], $this -> params['size']);
        } catch (\Exception $e) {
            return $this -> writeJson(Status::CODE_BAD_REQUEST, "服务异常");
        }
        if (!empty($data['lists'])) {
            foreach ($data['lists'] as &$list) {
                $list['create_time'] = date("Y-m-d H:i:s", $list['create_time']);
                //视频时长  00:01:07
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }
        }
        return $this -> writeJson(Status::CODE_OK, "OK", $data);

    }

    /**
     * description  第二套方案：读取静态缓存数据
     * @return bool
     */
    public function lists ()
    {
        $catId = !empty($this -> params['cat_id']) ? intval($this -> params['cat_id']) : 0;
        //文件路径
        $videoFile = EASYSWOOLE_ROOT . "/webroot/video/json/" . $catId . ".json";
        //判断文件是否存在，如果存在，获取json文件
        $videoData = is_file($videoFile) ? file_get_contents($videoFile) : [];
        $videoData = !empty($videoData) ? json_decode($videoData, true) : [];
        $count = count($videoData);

        //返回分页数据
        return $this -> writeJson(Status::CODE_OK, "OK", $this -> getPagingDatas($count, $videoData));

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