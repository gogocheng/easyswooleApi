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
        if(empty($this->tableName)){
            throw new \Exception("table error");
        }
        $db = Di::getInstance()->get('MYSQL');
        if($db instanceof \MysqliDb){
            $this->db = $db;
        }else{
            throw new \Exception("db error");
        }
    }

    public function add ($data)
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }
        return $this -> db -> insert($this -> tableName, $data);
    }
}