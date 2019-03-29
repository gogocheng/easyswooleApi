<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2019/3/29
 * Time: 20:29
 */

namespace App\Model;


class Video extends Base
{
    public $tableName = "video";

    public function getVideoData ($condition = [], $page = 1, $size = 10)
    {
        if (!empty($size)) {
            $this -> db -> pageLimit = $size;
        }
        $this -> db -> paginate($this -> tableName, $page);
        //打印sql
        echo $this -> db -> getLastQuery();
    }


}