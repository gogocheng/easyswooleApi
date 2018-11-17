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

    /**
     * description   redis   get
     * @param $key
     * @return bool|string
     */
    public function get ($key)
    {
        if (empty($key)) {
            return "";
        }
        $res = $this -> redis -> get($key);
        return $res;
    }

    /**
     * description  redis   lpop
     * @param $key
     * @return string
     */
    public function lPop ($key)
    {
        if (empty($key)) {
            return "";
        }
        $res = $this -> redis -> lPop($key);
        return $res;
    }

    /**
     * description  redis   rpush
     * @param $key
     * @param $value
     * @return bool|int|string
     */
    public function rPush ($key,$value)
    {
        if (empty($key)) {
            return "";
        }
        $res = $this -> redis -> rPush($key,$value);
        return $res;
    }
}