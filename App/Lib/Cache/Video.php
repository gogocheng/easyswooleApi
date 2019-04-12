<?php


namespace App\Lib\Cache;


/**
 * description  首页列表定时缓存数据
 * * Class Video
 * @package App\Lib\Cache
 */
class Video
{
    public function setIndexVideo ()
    {
//        $catIds = \Yaconf::get("category.cats");
//        //把0插入到数组开头
//        array_unshift($catIds,0);
        $catIds = [ 1, 2, 3 ];
        array_unshift($catIds, 0);
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
            //写入json到文件
            $flag = file_put_contents(EASYSWOOLE_ROOT . "/webroot/video/json/" . $catId . ".json", json_encode($data));
            if (empty($flag)) {
                echo "cat_id:" . $catId . "put data error" . PHP_EOL;
            } else {
                echo "cat_id:" . $catId . "put data success" . PHP_EOL;
            }
        }


    }
}