<?php

/**
 * 公共函数库.
 * 
 * @author aic <41262633@qq.com>
 */

/**
 * Class Common.
 */
class Common {

    /**
     * 404 not found.
     * 
     * @param string $message 提示信息.
     * @param string $returnUrl 跳转网址.
     * 
     * @return void
     */
    public static function noPage($message = '', $returnUrl = '') {
        header('HTTP/1.1 404 Not Found');
        header('status: 404 Not Found');

        require TPL_PATH . '_404.html';
        exit;
    }

    /**
     * ajax输出.
     * 
     * @param mixed $input 输入参数.
     * 
     * @return void
     */
    public static function ajaxOut($input) {
        die(json_encode($input));
    }

    /**
     * ajax返回正确.
     * 
     * @param string $message 提示信息.
     * @param array $data 附加的数据.
     * 
     * @return void
     */
    public static function ajaxReturnSuccess($message = '', $data = array()) {
        $return = array(
            'error' => 0,
            'message' => $message,
            'data' => $data,
        );
        static::ajaxOut($return);
    }

    /**
     * ajax返回失败.
     * 
     * @param string $message 提示信息.
     * 
     * @return void
     */
    public static function ajaxReturnFalse($message = '') {
        $return = array(
            'error' => 1,
            'message' => $message,
        );
        static::ajaxOut($return);
    }

    /**
     * 错误提示.
     * 
     * @param string $message 错误提示信息.
     * 
     * @return void
     */
    public static function showErrorMsg($message = '') {
        if (static::isAjaxRequest()) {
            static::ajaxReturnFalse($message);
        } else {
            static::noPage($message);
        }
    }

    /**
     * 判断是否是post提交操作.
     * 
     * @return boolean.
     */
    public static function isPost() {
        return isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === 'POST' ? true : false;
    }

    /**
     * 判断是否是ajax请求.
     * 
     * @return boolean.
     */
    public static function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' ? true : false;
    }

    /**
     * 页面跳转.
     * 
     * @param string $url 页面跳转url.
     * 
     * @return void
     */
    public static function redirect($url) {
        header("Location: $url");
        // 跳转的后续代码, 强制终止.
        exit(0);
    }

    /**
     * 数组以某个键值为索引返回.
     * 
     * @param array $arr 原始数组值.
     * @param string $key 返回数组索引键.
     * 
     * @return array.
     */
    public static function getArrByKey($arr = array(), $key = '') {
        $return = array();
        foreach ($arr as $a) {
            if (isset($a[$key])) {
                $return[$key] = $a;
            } else {
                $return[] = $a;
            }
        }
        return $return;
    }

}
