<?php


namespace App\Lib\Cache;


use EasySwoole\Core\Component\Cache\Cache;
use EasySwoole\Core\Component\Di;
use mysql_xdevapi\Exception;

/**
 * description  首页列表定时缓存数据
 * * Class Video
 * @package App\Lib\Cache
 */
class Video
{

    /**
     * description   按照不同的方式写入缓存
     * @throws \Exception
     */
    public function setIndexVideo ()
    {
        $catIds = \Yaconf ::get('category.cats');
        $catIds = array_keys($catIds);
        //把0插入到数组开头
        array_unshift($catIds, 0);
        //获取读取缓存的方式
        $cacheType = \Yaconf ::get('base.indexCacheWay');
        //实例化模型video
        $modelObj = new \App\Model\Video();

        //写 video  json 缓存数据
        foreach ($catIds as $catId) {
            $condition = [];
            if (!empty($catIds)) {
                $condition['cat_id'] = $catId;
            }
            try {
                $data = $modelObj -> getVideoCacheData($condition);
            } catch (\Exception $e) {
                $data = [];
            }
            if (empty($data)) {
                continue;
            }
            foreach ($data as &$list) {
                $list['create_time'] = date("Y-m-d H:i:s", $list['create_time']);
                //视频时长  00:01:07
                $list['video_duration'] = gmstrftime("%H:%M:%S", $list['video_duration']);
            }
            switch ($cacheType) {
                case 'file':
                    //缓存写入json文件
                    $res = file_put_contents($this -> getVideoCatIdFile($catId), json_encode($data));
                    break;
                case 'table':
                    //easyswoole封装swoole中的table（共享内存），写入缓存数据
                    $res = Cache ::getInstance() -> set($this -> getCatKey($catId), $data);
                    break;
                case 'redis':
                    //缓存写入redis
                    $res = Di ::getInstance() -> get("REDIS") -> set($this -> getCatKey($catId), $data);
                    break;
                default:
                    throw new \Exception("请求不合法");
                    break;
            }
            if (empty($res)) {
                //todo   记录日志

            }
            //写入json到文件
//            $flag = file_put_contents(EASYSWOOLE_ROOT . "/webroot/video/json/" . $catId . ".json", json_encode($data));

            //easyswoole封装swoole中的table（共享内存），写入缓存数据
//            Cache ::getInstance() -> set("video_cache_data" . $catId, $data);
//            if (empty($flag)) {
//                echo "cat_id:" . $catId . "put data error" . PHP_EOL;
//            } else {
//                echo "cat_id:" . $catId . "put data success" . PHP_EOL;
//            }

            //redis写入缓存
//            Di ::getInstance() -> get("REDIS") -> set($this -> getCatKey($catId), $data);
//            print_r(Di ::getInstance() -> get("REDIS"));

        }
    }


    /**
     * description   获取写入json文件路径
     * @param $catId
     * @return string
     */
    public function getVideoCatIdFile ($catId)
    {
        return EASYSWOOLE_ROOT . "/webroot/video/json/" . $catId . ".json";
    }


    /**
     * description   获取table中的key
     * @param $catId
     * @return string
     */
    public function getCatKey ($catId)
    {
        return "video_cache_data" . $catId;
    }

    /**
     * description   按照不同的方式获取缓存数据
     * @param $catId
     * @return array|false|mixed|string|null
     * @throws \Exception
     */
    public function getCache ($catId)
    {
        //获取读取缓存的方式
        $cacheType = \Yaconf ::get('base.indexCacheWay');
        switch ($cacheType) {
            case 'file':
                $videoFile = $this -> getVideoCatIdFile($catId);
                $videoData = is_file($videoFile) ? file_get_contents($videoFile) : [];
                $videoData = !empty($videoData) ? json_decode($videoData, true) : [];
                break;
            case 'table':
                $videoData = Cache ::getInstance() -> get($this -> getCatKey($catId));
                $videoData = !empty($videoData) ? $videoData : [];
                break;
            case 'redis':
                $videoData = Di ::getInstance() -> get("REDIS") -> get($this -> getCatKey($catId));
                $videoData = !empty($videoData) ? json_decode($videoData, true) : [];
                break;
            default:
                throw new \Exception("请求不合法");
                break;
        }
        return $videoData;
    }
}