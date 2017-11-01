<?php

/**
 * 七牛文件上传.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Logic;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class Uploader {

    // 用于签名的公钥和私钥.
    private $accessKey = '';
    private $secretKey = '';
    private $auth = null;
    private $bucket = '';

    public function __construct() {
        $this->accessKey = \Config\Qiniu::$accessKey;
        $this->secretKey = \Config\Qiniu::$secretKey;
        $this->bucket = \Config\Qiniu::$bucket;
        $this->auth = new Auth($this->accessKey, $this->secretKey);
    }
}
