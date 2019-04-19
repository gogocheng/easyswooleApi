<?php


namespace App\HttpController\Api;


use App\Lib\Es\EsVideo;
use EasySwoole\Core\Http\Message\Status;

/**
 * description  es搜索
 * Class Search
 * @package App\HttpController\Api\]
 */
class Search extends Base
{

    /**
     * description  站内搜索
     */
    public function index ()
    {
        //关键词
        $keyword = $this -> params['keyword'];

        if (empty($keyword)) {
            return $this -> writeJson(Status::CODE_OK, 'ok', $this -> getPagingDatas(0, []));
        }
        $esObj = new EsVideo();
        $res = $esObj -> searchByName($keyword, $this -> params['from'], $this -> params['size']);

        if (empty($res)) {
            return $this -> writeJson(Status::CODE_OK, 'ok', $this -> getPagingDatas(0, []));
        }

        $hits = $res['hits']['hits'];
        $total = $res['hits']['total'];
        if(empty($total)){
            return $this -> writeJson(Status::CODE_OK, 'ok', $this -> getPagingDatas(0, []));
        }
        foreach ($hits as $hit) {
            $source = $hit['_source'];
            $resData[] = [
                'id' => $hit['_id'],
                'name' => $source['name'],
                'image' => $source['image'],
                'uploader' => $source['uploader'],
                'create_time' => '',
                'video_duration' => '',
                'keyword' => [ $keyword ]
            ];
        }
        return $this -> writeJson(Status::CODE_OK, 'ok', $this -> getPagingDatas($total, $resData));
    }
}