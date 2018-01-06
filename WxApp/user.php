<?php

/**
 * 查询用户的weixin user_openid.
 * url = app.imeiwen.org.
 */
// 返回实例
//echo '﻿﻿{"openId":"ogfoT0V91Vcq-omBc2vUvp0sS540","nickName":"阿D","gender":1,"language":"zh_CN","city":"","province":"","country":"Vanuatu","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTIkMt27C2AF1mUz6iaV1z5T8Tgj2KUgvpAA1nkXZe640FJqPMVD3FmcW7UHL0MGMF887AzZgnicMkvw/0","unionId":"owotc1i2RoZRz3lrx__ONALRPDCY","watermark":{"timestamp":1515133339,"appid":"wx809054e9326721af"}}';
require_once "./aes/wxBizDataCrypt.php";
$appid = 'wx809054e9326721af';
$appsecret = "28f8569d2e540b7256e0dd7ca209d26c";
$code = isset($_GET['code']) ? trim($_GET['code']) : '';
$res = '';
if ($code) {
    $getSessionUrl = "https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appsecret}&js_code={$code}&grant_type=authorization_code";
    $res = file_get_contents($getSessionUrl);
}
echo $res;
exit;

$sessionKey = $res && isset($res['session_key']) ? $res['session_key'] : '';
$encryptedData = isset($_GET['data']) ? trim($_GET['data']) : '';
$encryptedData = str_replace(" ", "+", $encryptedData);
$iv = isset($_GET['iv']) ? trim($_GET['iv']) : '';
$iv = str_replace(" ", "+", $iv);

if ($sessionKey && $encryptedData && $iv) {
    $pc = new WXBizDataCrypt($appid, $sessionKey);
    $errCode = $pc->decryptData($encryptedData, $iv, $data);

    if ($errCode == 0) {
        echo $data;
        exit;
    }
}
echo '';
exit;