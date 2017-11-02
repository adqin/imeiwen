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

    /**
     * 上传到七牛.
     * 
     * @param string $local 本地文件地址.
     * @param string $save 保存到七牛的文件名.
     * 
     * @return string.
     */
    public function upload($local, $save) {
        $token = $this->auth->uploadToken($this->bucket, $save);
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $save, $local);
        if (is_null($err)) {
            // var_dump($ret);
            return $ret['key'];
        } else {
            // var_dump($err);
            return '';
        }
    }

}
