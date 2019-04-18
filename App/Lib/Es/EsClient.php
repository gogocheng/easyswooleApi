<?php


namespace App\Lib\Es;

use EasySwoole\Core\AbstractInterface\Singleton;
use Elasticsearch\ClientBuilder;

/**
 * description  es基础类库
 * Class EsClient
 * @package App\Lib\Es
 */
class EsClient
{
    //单例模式
    use Singleton;

    public $esClient = null;

    private function __construct ()
    {
        $config = \Yaconf ::get("es");
        try {
            $this -> esClient = ClientBuilder ::create() -> setHosts([ $config['host'] . ":" . $config['port'] ]) -> build();
        } catch (\Exception $e) {
            //todo
        }

        if (empty($this -> client)) {
            //todo
        }


    }

    public function __call ($name, $arguments)
    {
        // TODO: Implement __call() method.
        return $this -> esClient -> $name(...$arguments);

    }
}