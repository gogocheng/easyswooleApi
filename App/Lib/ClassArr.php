<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2019/3/29
 * Time: 18:20
 */

namespace App\Lib;

/**
 * description   做一些反射机制有关的处理
 * Class ClassArr
 * @package App\Lib
 */
class ClassArr
{
    public function uploadClassStat ()
    {
        return [
            "image" => "\App\Lib\Upload\Image",
            "video" => "\App\Lib\Upload\Video"
        ];
    }

    /**
     * description    php反射机制
     * @param $type   文件类型
     * @param $supportedClass  实例化的文件类
     * @param array $params    数组
     * @param bool $needInstance   是否需要实例化
     * @return bool|object
     * @throws \ReflectionException
     */
    public function initClass ($type, $supportedClass, $params = [], $needInstance = true)
    {
        if (!array_key_exists($type, $supportedClass)) {
            return false;
        }

        $className = $supportedClass[$type];

        return $needInstance ? (new \ReflectionClass($className)) -> newInstanceArgs($params) : $className;
    }
}