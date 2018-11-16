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
            $redisConfig = Config ::getInstance() -> getConf("redis");
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
     * @param $key   键名
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
}