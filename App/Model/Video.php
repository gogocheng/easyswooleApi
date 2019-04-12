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

    /**
     * description  通过条件获取数据
     * @param array $condition
     * @param int $page 页码
     * @param int $size 页码跨度
     * @return array
     * @throws \Exception
     */
    public function getVideoData ($condition = [], $page = 1, $size = 100)
    {
        if (!empty($condition['cat_id'])) {
            //分类id
            $this -> db -> where('cat_id', $condition['cat_id']);
        }
        //状态是1
        $this->db->where('status',1);
        if (!empty($size)) {
            $this -> db -> pageLimit = $size;
        }
        //排序
        $this -> db -> orderBy('id', 'desc');
        //分页
        $res = $this -> db -> paginate($this -> tableName, 1);
        //打印sql
//        echo $this -> db -> getLastQuery();
        $data = [
            'total_page' => $this -> db -> totalPages,
            'page_size' => $size,
            'count' => intval($this -> db -> totalCount),
            'lists' => $res
        ];

        return $data;
    }


    /**
     * description   获取video列表缓存数据
     * @param array $condition
     * @param int $page
     * @param int $size
     * @return array
     * @throws \Exception
     */
    public function getVideoCacheData ($condition = [], $page = 1, $size = 100)
    {
        if (!empty($condition['cat_id'])) {
            //分类id
            $this -> db -> where('cat_id', $condition['cat_id']);
        }
        //状态是1
        $this->db->where('status',1);
        if (!empty($size)) {
            $this -> db -> pageLimit = $size;
        }
        //排序
        $this -> db -> orderBy('id', 'desc');
        //分页
        $res = $this -> db -> paginate($this -> tableName, $page);
        //打印sql
//        echo $this -> db -> getLastQuery();
//        $data = [
//            'total_page' => $this -> db -> totalPages,
//            'page_size' => $size,
//            'count' => intval($this -> db -> totalCount),
//            'lists' => $res
//        ];

        return $res;
    }


}