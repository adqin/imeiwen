<?php

/**
 * 控制器基类.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller;

/**
 * Class Base Controller.
 */
class Base {

    protected $data = array(); // 模板变量存储.
    protected $param = array();
    protected $isMobile = false; // 是否是移动设备浏览.
    protected $version = 20171231; // 版本号.

    /**
     * 构造函数.
     * 
     * @param mixed $vals 附加的变量.
     */

    public function __construct($vals = array()) {
        $this->param = $vals;
        $this->isMobile();
        
        $this->assign('is_mobile', $this->isMobile);
        $this->assign('version', $this->version);
    }

    /**
     * 模板赋值.
     * 
     * @param string $key 赋值的变量名.
     * @param mixed $data 赋值.
     * 
     * @return void
     */
    protected function assign($key, $data) {
        $this->data[$key] = $data;
    }

    /**
     * 视图渲染.
     * 
     * @param string $tmpl 模板名.
     * 
     * @return void
     */
    protected function display($tmpl) {
        $tpl_file = TPL_PATH . $tmpl . '.html'; // 模板文件地址.

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
     * 获取表单提交的数据(post).
     * 
     * @param string $key 表单名.
     * 
     * @return mixed.
     */
    protected function getPost($key = '') {
        $value = isset($_POST[$key]) ? $_POST[$key] : '';
        return $this->filterData($value);
    }

    /**
     * 获取表单提交的数据(get).
     * 
     * @param string $key 表单名.
     * 
     * @return mixed.
     */
    protected function getGet($key = '') {
        $value = isset($_GET[$key]) ? trim($_GET[$key]) : '';
        return $this->filterData($value);
    }

    /**
     * 设置cookie.
     * 
     * @param string $key 设置的cookie key.
     * @param mixed $value 保存到cookie中的值.
     * @param integer $expireTime cookie有效时间.
     * @param string $path 生效的目录.
     * 
     * @return void
     */
    protected function setCookie($key, $value, $expireTime = 7200, $path = '/') {
        // 将值都json_encode编码.
        $value = json_encode($value);
        setcookie($key, $value, time() + $expireTime, $path, "", false, true);
    }

    /**
     * 获取cookie.
     * 
     * @param string $key 获取的cookie key.
     * 
     * @return mixed.
     */
    protected function getCookie($key = '') {
        $value = isset($_COOKIE[$key]) ? trim($_COOKIE[$key]) : '';
        if ($value) {
            $value = json_decode($value, true);
        }
        return $this->filterData($value);
    }

    /**
     * 格式化与过滤数据.
     * 
     * @param mixed $data 待过滤的数据.
     * 
     * @return mixed.
     */
    protected function filterData($data) {
        if (!$data) {
            return $data;
        }

        if (is_array($data)) {
            // 递归处理数组.
            foreach ($data as $k => $v) {
                $data[$k] = $this->filterData($v);
            }
        } else {
            // 过滤两边空格.
            $data = trim($data);
            // 过滤html标签.
            $data = strip_tags($data, '<a><div><p><img>');
            // 使用反斜线引用字符串.
            $data = addslashes($data);
        }

        return $data;
    }

    /**
     * 设置是否是移动设备浏览.
     */
    protected function isMobile() {
        $c = $this->getCookie('is_mobile');
        if ($c === '') {
            // 未设置cookie.
            $r = \Common::isMobile();
            $this->setCookie('is_mobile', $r, time() + 2592000);
            $this->isMobile = $r;
        } else {
            $this->isMobile = $c;
        }
    }

    /**
     * 析构函数.
     */
    public function __destruct() {
        unset($this->data);
        unset($this->param);
    }

}
