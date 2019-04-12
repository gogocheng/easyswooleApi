<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use App\Lib\Cache\Video;
use App\Lib\Process\ConsumerTest;
use App\Lib\Redis\Redis;
use \EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Component\Crontab\CronTab;
use \EasySwoole\Core\Swoole\ServerManager;
use \EasySwoole\Core\Swoole\EventRegister;
use \EasySwoole\Core\Http\Request;
use \EasySwoole\Core\Http\Response;
use EasySwoole\Core\Component\Di;
use EasySwoole\Core\Utility\File;
use EasySwoole\Core\Swoole\Process\ProcessManager;


Class EasySwooleEvent implements EventInterface
{

    public static function frameInitialize (): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');
        // 载入项目 Conf 文件夹中所有的配置文件
        self ::loadConf(EASYSWOOLE_ROOT . '/Config');
    }

    public static function loadConf ($ConfPath)
    {
        $Conf = Config ::getInstance();
        $files = File ::scanDir($ConfPath);
        foreach ($files as $file) {
            $data = require_once $file;
            $Conf -> setConf(strtolower(basename($file, '.php')), (array)$data);
        }
    }

    public static function mainServerCreate (ServerManager $server, EventRegister $register): void
    {
        // TODO: Implement mainServerCreate() method.
        //mysql
        Di ::getInstance() -> set('MYSQL', \MysqliDb::class, Array (
                'host' => '192.168.1.39',
                'username' => 'root',
                'password' => '123456',
                'db' => 'video',
                'port' => 3306,
                'charset' => 'utf8' )
        );
        //redis
        Di ::getInstance() -> set('REDIS', Redis ::getInstance());

        //消息队列
        $allNum = 3;
        for ($i = 0; $i < $allNum; $i++) {
            ProcessManager ::getInstance() -> addProcess("consumer_{$i}", ConsumerTest::class);
        }
        //调用crontab做定时任务
        $cacheVideoObj = new Video();  //实例化缓存类
        CronTab ::getInstance()
            -> addRule("test_crontab", "*/1 * * * *", function () use ($cacheVideoObj) {
                $cacheVideoObj -> setIndexVideo();
            });

    }

    public static function onRequest (Request $request, Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public static function afterAction (Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }


}