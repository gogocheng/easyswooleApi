<?php
/**
 * Created by PhpStorm.
 * User: lining
 * Date: 2019/3/29
 * Time: 15:33
 */

namespace App\Lib\Upload;


class Video extends Base
{
    //文件类型
    public $fileType = 'video';
    //文件最大
    public $maxSize = 122;
    //文件后缀
    public $fileExtTypes = [
        'mp4','x-flv','m3u8'
    ];
}