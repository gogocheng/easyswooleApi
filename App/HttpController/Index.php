<?php

namespace App\HttpController;

use EasySwoole\Core\Http\AbstractInterface\Controller;
use Elasticsearch\ClientBuilder;

    /**
     * Class Index
     * @package App\HttpController
     */
class Index extends Controller
{
    /**
     * 首页方法
     * @author : evalor <master@evalor.cn>
     */
    function index ()
    {
//        $this->response()->withHeader('Content-type', 'text/html;charset=utf-8');
//        $this->response()->write('<div style="text-align: center;margin-top: 30px"><h2>欢迎使用EASYSWOOLE</h2></div></br>');
//        $this->response()->write('<div style="text-align: center">您现在看到的页面是默认的 Index 控制器的输出</div></br>');
//        $this->response()->write('<div style="text-align: center"><a href="https://www.easyswoole.com/Manual/2.x/Cn/_book/Base/http_controller.html">查看手册了解详细使用方法</a></div></br>');

        //测试 php-elasticsearch
        $params = [
            'index' => "video_easyswoole",
            'type' => "video",
            'id' => 1
        ];
        //   实例化es
        $client = ClientBuilder ::create() -> setHosts([ "127.0.0.1:9200" ]) -> build();
        //获取数据
        $res = $client -> get($params);

    }

    function test ()
    {
        return $this -> writeJson(200, 'ok', [ 'ab' => 123 ]);
    }
}