<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2019/3/29
 * Time: 15:36
 */

namespace App\Lib\Upload;


use App\Lib\Utils;
use Composer\Repository\PackageRepository;

class Base
{
    /**
     * @var string   上传文件 file - key
     */
    public $type = "";

    public function __construct ($request, $type = null)
    {
        $this -> request = $request;
        if (empty($type)) {
            // 获取上传文件数组
            $files = $this -> request -> getSwooleRequest() -> files;
            $types = array_keys($files);
            $this -> type = $types[0];
        } else {
            $this -> type = $type;
        }

    }

    /**
     * description   文件上传
     * @return bool
     * @throws \Exception
     */
    public function upload ()
    {
        if ($this -> type != $this -> fileType) {
            return false;
        }
        //获取上传文件信息
        $file = $this -> request -> getUploadedFile($this -> type);
        //上传文件大小
        $this -> size = $file -> getSize();
        //检查文件大小
        $this -> checkSize();
        //文件名
        $fileName = $file -> getClientFileName();
        //文件类型
        $this -> clientMediaType = $file -> getClientMediaType();
        //检测文件类型
        $this -> checkMediaType($this -> clientMediaType);
        //文件保存路径
        $filePath = $this -> getFile($fileName);

        $flag = $file -> moveTo($filePath);
        if (!empty($flag)) {
            return $this -> file;
        }
        return false;
    }

    /**
     * description   检测文件大小
     * @return bool
     */
    public function checkSize ()
    {
        if (empty($this -> size)) {
            return false;
        }

        if ($this -> size > $this -> maxSize) {
            return false;
        }
        return true;
    }

    /**
     * description   检测文件类型
     * @return bool
     * @throws \Exception
     */
    public function checkMediaType ()
    {
        $clientMediaType = explode('/', $this -> clientMediaType);
        $clientMediaType = $clientMediaType[1] ?? "";
        if (empty($clientMediaType)) {
            throw new \Exception("上传{$this->type}文件不合法");
        }
        if (!in_array($clientMediaType, $this -> fileExtTypes)) {
            throw new \Exception("上传{$this->type}文件不合法");
        }
        return true;
    }

    /**
     * description    获取文件上传路径
     * @param $fileName
     * @return string
     */
    public function getFile ($fileName)
    {
        //文件名数组
        $pathinfo = pathinfo($fileName);
        //后缀
        $extension = $pathinfo['extension'];
        $dirName = "/" . $this -> type . "/" . date("Y") . "/" . date("m");
        //文件目录
        $dir = EASYSWOOLE_ROOT . "/webroot" . $dirName;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        //生产唯一路径
        $baseName = "/" . Utils ::getFileKey($fileName) . "." . $extension;
        $this -> file = $dirName . $baseName;
        $filePath = $dir . $baseName;
        return $filePath;
    }
}