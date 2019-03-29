<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2019/3/29
 * Time: 18:05
 */

namespace App\Lib\Upload;


class Image extends Base
{
    //文件类型
    public $fileType = 'image';
    //文件最大
    public $maxSize = 122;
    //文件后缀
    public $fileExtTypes = [
        'png','jpeg','jpg'
    ];
}