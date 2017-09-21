<?php

/**
 * 控制器基类.
 */
class Controller {

    protected $data = array(); // 模板变量存储.

    /**
     * 模板赋值.
     */

    protected function assign($key, $data) {
        $this->data[$key] = $data;
    }

    /**
     * 视图渲染.
     */
    protected function display($tmpl) {
        $tpl_file = APP_PATH . 'view/' . $tmpl . '.tpl.php'; // 模板文件地址.

        if (!file_exists($tpl_file)) {
            exit('模板文件' . $tmpl . '不存在');
        }

        if (!empty($this->data)) {
            // 从数组中将变量导入到当前的符号表.
            extract($this->data);
        }

        // 打开输出控制缓冲.
        ob_start();
        require $tpl_file;
        // 冲刷出（送出）输出缓冲区内容并关闭缓冲.
        ob_end_flush();
        
        // display输出后强制终止后续代码.
        exit(0);
    }

    /**
     * 是否是post提交操作.
     */
    protected function isPost() {
        return isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == 'POST' ? true : false;
    }

    /**
     * 获取表单提交的数据(post).
     */
    protected function getPost($key = '') {
        $value = isset($_POST[$key]) ? $_POST[$key] : '';
        if (is_array($value)) {
            return $value;
        }
        
        $value = trim($value);
        return strip_tags($value);
    }

    /**
     * 获取表单提交的数据(get).
     */
    protected function getGet($key = '') {
        $value = isset($_GET[$key]) ? trim($_GET[$key]) : '';
        return strip_tags($value);
    }

    /**
     * 设置cookie.
     */
    protected function setCookie($key, $value, $expireTime = 7200, $path = '/') {
        $value = json_encode($value);
        setcookie($key, $value, time() + $expireTime, $path);
    }

    /**
     * 获取cookie.
     */
    protected function getCookie($key = '') {
        $value = isset($_COOKIE[$key]) ? trim($_COOKIE[$key]) : '';
        $value = strip_tags($value);
        return json_decode($value, true);
    }

    /**
     * 判断是否登陆后台.
     */
    protected function adminIslogin() {
        $login_flag = Config::$admin['login_flag'];
        $login_value = Config::$admin['login_value'];

        if ($login_value != $this->getCookie($login_flag)) {
            return false;
        }

        return true;
    }

}
