<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/11/16
 * Time: 21:50
 */

namespace App\Lib\Redis;

use EasySwoole\Config;
use EasySwoole\Core\AbstractInterface\Singleton;

/**
 * description  redis基础类库
 * Class Redis
 * @package App\Lib\Redis
 */
class Redis
{
    //单例模式
    use Singleton;

    public $redis = "";


    private function __construct ()
    {
        if (!extension_loaded('redis')) {
            throw new \Exception('redis.so未安装');
        }
        try {
            //框架获取配置文件
            //$redisConfig = Config ::getInstance() -> getConf("redis");
            //yaconf获取配置文件
            $redisConfig = \Yaconf ::get('redis');
            $this -> redis = new \Redis();
            $res = $this -> redis -> connect($redisConfig['host'], $redisConfig['port'], $redisConfig['timeout']);

        } catch (\Exception $e) {
            throw new \Exception('redis服务异常');
        }
        if ($res === false) {
            throw new \Exception('redis连接失败');
        }
    }

//    /**
//     * description   redis   get
//     * @param $key
//     * @return bool|string
//     */
//    public function get ($key)
//    {
//        if (empty($key)) {
//            return "";
//        }
//        $res = $this -> redis -> get($key);
//        return $res;
//    }
//
//    /**
//     * description   redis   set
//     * @param $key
//     * @return bool|string
//     */
//    public function set ($key, $value, $time = 0)
//    {
//
//        if (!$key) {
//            return '';
//        }
//        if (is_array($value)) {
//            $value = json_encode($value);
//        }
//        if ($time == 0) {
//            return $this -> redis -> set($key, $value);
//        }
//        //设置成用不超时
//
//        return $this -> redis -> setex($key, $value, $time);
//    }
//
//    /**
//     * description  redis   lpop
//     * @param $key
//     * @return string
//     */
//    public function lPop ($key)
//    {
//        if (empty($key)) {
//            return "";
//        }
//        $res = $this -> redis -> lPop($key);
//        return $res;
//    }
//
//    /**
//     * description  redis   rpush
//     * @param $key
//     * @param $value
//     * @return bool|int|string
//     */
//    public function rPush ($key, $value)
//    {
//        if (empty($key)) {
//            return "";
//        }
//        $res = $this -> redis -> rPush($key, $value);
//        return $res;
//    }
//
//    /**
//     * description   redis   zincrby   对有序集合中指定成员的分数加上增量 increment
//     * @param $key
//     * @param $number
//     * @param $member
//     * @return bool|float
//     */
//    public function zincrby ($key, $number, $member)
//    {
//        if (empty($key) || empty($number)) {
//            return false;
//        }
//        return $this -> redis -> zincrby($key, $number, $member);
//    }
//
//    /**
//     * description  返回有序集中，指定区间内的成员。
//     * @param $key
//     * @param $start
//     * @param $stop
//     * @param $type
//     * @return array|bool
//     */
//    public function zrevrange111 ($key, $start, $stop, $type)
//    {
//        if (empty($key)) {
//            return false;
//        }
//        return $this -> redis -> zrevrange($key, $start, $stop, $type);
//    }

    /**
     * description    当类中不存在该方法，直接调用call，实现底层redis相关的方法，就可以不用封装redis方法
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call ($name, $arguments)
    {
        // TODO: Implement __call() method.
        return $this -> redis -> $name(...$arguments);

    }
}