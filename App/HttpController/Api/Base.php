<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2018/11/15
 * Time: 22:38
 */

namespace App\HttpController\Api;


use EasySwoole\Core\Http\AbstractInterface\Controller;

class Base extends Controller
{
    /**
     * @var array 请求的参数数组
     */
    public $params = [];

    public function index ()
    {
        // TODO: Implement index() method.
    }

    public function onRequest ($action): ?bool
    {
        $this -> getParams();
//        $this->writeJson(403,'无权限',[]);
        return true;
    }

    public function getParams ()
    {
        //抽离url请求参数放到基类
        $params = $this -> request() -> getRequestParam();
        $params['page'] = !empty($params['page']) ? intval($params['page']) : 1;
        $params['size'] = !empty($params['size']) ? intval($params['size']) : 5;
        $params['from'] = ($params['page'] - 1) * $params['size'];
        $this -> params = $params;
    }

    public function onException (\Throwable $throwable, $actionName): void
    {
//        parent ::onException($throwable, $actionName); // TODO: Change the autogenerated stub
        $this -> writeJson(400, '请求不合法', []);
    }

    /**
     * description   重写writejson
     * @param int $statusCode 状态码
     * @param null $result 返回值
     * @param null $msg 消息提示
     * @return bool
     */
    protected function writeJson ($statusCode = 200, $result = null, $msg = null)
    {
        if (!$this -> response() -> isEndResponse()) {
            $data = Array (
                "code" => $statusCode,
                "result" => $result,
                "msg" => $msg
            );
            $this -> response() -> write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this -> response() -> withHeader('Content-type', 'application/json;charset=utf-8');
            $this -> response() -> withStatus($statusCode);
            return true;
        } else {
            trigger_error("response has end");
            return false;
        }
    }


    /**
     * description   分页数据
     * @param $count
     * @param $data
     * @return array
     */
    public function getPagingDatas ($count, $data)
    {
        //总页数
        $totalPage = ceil($count / $this -> params['size']);

        $data = $data ?? [];

        //截取
        $data = array_slice($data, $this -> params['from'], $this -> params['size']);
        return [
            'total_page' => $totalPage,
            'page_size' => $this -> params['size'],
            'count' => intval($count),
            'lists' => $data
        ];
    }
}