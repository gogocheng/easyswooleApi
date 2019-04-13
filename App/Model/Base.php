<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2019/3/29
 * Time: 20:30
 */

namespace App\Model;


use EasySwoole\Core\Component\Di;

class Base
{
    public function __construct ()
    {
        if (empty($this -> tableName)) {
            throw new \Exception("table error");
        }
        $db = Di ::getInstance() -> get("MYSQL");
        if ($db instanceof \MysqliDb) {
            $this -> db = $db;
        } else {
            throw new \Exception("db error");
        }
    }

    /**
     * description   添加
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public function add ($data)
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        return $this -> db -> insert($this -> tableName, $data);
    }

    /**
     * 通过ID 获取 基本信息
     *
     * @param [type] $id
     * @return void
     */
    public function getById($id) {
        $id = intval($id);
        if(empty($id)) {
            return [];
        }

        $this->db->where ("id", $id);
        $result = $this->db->getOne($this->tableName);
        return $result ?? [];
    }
}