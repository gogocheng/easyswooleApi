<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2019/3/29
 * Time: 20:21
 */

namespace App\HttpController\Api;


use EasySwoole\Core\Component\Logger;
use EasySwoole\Core\Utility\Validate\Rule;
use EasySwoole\Core\Http\Message\Status;
use EasySwoole\Core\Utility\Validate\Rules;

class Video extends Base
{
    //记录何种日志
    public $logType = "video";

    /**
     * 视频播放页面基本信息接口
     *
     * @return void
     */
    public function index ()
    {
        $id = intval($this -> params['id']);
        if (empty($id)) {
            return $this -> writeJson(Status::CODE_BAD_REQUEST, "请求不合法");
        }

        // 获取视频的基本信息
        try {
            $video = (new \App\Model\Video()) -> getById($id);
        } catch (\Exception $e) {
            // 记录日志 $e->getMessage()
            return $this -> writeJson(Status::CODE_BAD_REQUEST, "请求不合法");
        }
        if (!$video || $video['status'] != \Yaconf ::get("status.normal")) {
            return $this -> writeJson(Status::CODE_BAD_REQUEST, "该视频不存在");
        }
        $video['video_duration'] = gmstrftime("%H:%M:%S", $video['video_duration']);

        // 播放数统计逻辑
        // 投放task异步任务
//        TaskManager ::async(function () use ($id) {
//            // 逻辑
//            //sleep(10);
//            // redis
//
//            $res = Di ::getInstance() -> get("REDIS") -> zincrby(\Yaconf ::get("redis.video_play_key"), 1, $id);
//
//            // 按天记录
//        });

        return $this -> writeJson(200, 'OK', $video);
    }

    /**
     * description   添加数据
     * @return bool
     */
    public function add ()
    {
        //接受json数据
//        $params = $this -> request() -> getSwooleRequest();
//        $post = $params -> post;
//        $postJson = (array_keys($post))[0];
//        $postArray = json_decode($postJson, true);

        //接受form-data数据
        $params = $this -> request() -> getRequestParam();
        //写入日志
        Logger ::getInstance() -> log($this -> logType . "|add:" . json_encode($params));
        $data = [
            'name' => $params['name'],
            'image' => $params['image'],
            'url' => $params['url'],
            'content' => $params['content'],
            'cat_id' => $params['cat_id'],
            'create_time' => time(),
            'status' => 1
        ];
        //校验数据
        $ruleObj = new Rules();
        $ruleObj -> add('name', "视频名称错误")
            -> withRule(Rule::REQUIRED)
            -> withRule(Rule::MIN_LEN, 2)
            -> withRule(Rule::MAX_LEN, 20);
        $ruleObj -> add('url', "视频地址错误")
            -> withRule(Rule::REQUIRED)
            -> withRule(Rule::MIN_LEN, 2)
            -> withRule(Rule::MAX_LEN, 20);
        $ruleObj -> add('content', "视频描述错误")
            -> withRule(Rule::REQUIRED)
            -> withRule(Rule::MIN_LEN, 2)
            -> withRule(Rule::MAX_LEN, 20);
        $validate = $this -> validateParams($ruleObj);
        if ($validate -> hasError()) {
            return $this -> writeJson(Status::CODE_BAD_REQUEST, $validate -> getErrorList() -> first() -> getMessage());
        }
        //添加数据
        try {
            $videoObj = new \App\Model\Video();
            $videoId = $videoObj -> add($data);
        } catch (\Exception $e) {
            return $this -> writeJson(Status::CODE_BAD_REQUEST, $e -> getMessage());
        }
        if (!empty($videoId)) {
            return $this -> writeJson(Status::CODE_OK, 'ok', [ 'id' => $videoId ]);
        } else {
            return $this -> writeJson(Status::CODE_BAD_REQUEST, '提交有误', []);
        }

        return $this -> writeJson(200, 'ok', $params);
    }
}