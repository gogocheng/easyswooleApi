<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2019/3/29
 * Time: 14:47
 */

namespace App\HttpController\Api;


use App\Lib\ClassArr;
use App\Lib\Upload\Image;
use App\Lib\Upload\Video;

/**
 * description  文件上传
 * Class Upload
 * @package App\HttpController\Api
 */
class Upload extends Base
{
    public function file ()
    {

        $request = $this -> request();

        $files = $request -> getSwooleRequest() -> files;
        $types = array_keys($files);
        $type = $types[0];
        if (empty($type)) {
            return $this -> writeJson(400, "上传文件不合法", []);
        }
        //判断上传文件类型
        //方法一  这种方法不利于维护
//        if ($type === "image") {
//            $obj = "\App\Lib\Upload\Image";
//        } elseif ($type === "video") {
//            $obj = "\App\Lib\Upload\Video";
//        }


        try {
            //方法二  PHP的反射机制
            $classObj = new ClassArr();
            $classStats = $classObj -> uploadClassStat();
            $uploadObj = $classObj -> initClass($type, $classStats, [ $request, $type ]);
            $file = $uploadObj -> upload();
        } catch (\Exception $e) {
            return $this -> writeJson(400, $e -> getMessage(), []);
        }
        if (empty($file)) {
            return $this -> writeJson(400, "上传失败", []);
        }
        $data = [
            'url' => $file
        ];
        return $this -> writeJson(200, "ok", $data);
    }
}