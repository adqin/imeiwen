<?php
/**
 * 系统配置类.
 */
class Config {

    /**
     * 网站信息.
     */
    public static $site = array(
        'site_name' => '你问问',
        'site_note' => '常常问自己',
        'site_domain' => 'niwenwen.com',
    );

    /**
     * 后台管理.
     */
    public static $admin = array(
        // admin2017
        'username' => 'c80e999bdba0e8956428491050529392',
        // Admin2017@Wecms
        'passwd' => 'ddd062a724c8d20b601cde679ccf14c7',
        // cookie 标识
        // is_loginok
        'login_flag' => '1e1b9af7836413e948f4ef7b28216f23',
        // login_isok
        'login_value' => '0552e52ee00b460288c4a1f8957b96ac',
    );

    /**
     * js/css资源.
     */
    public static $source = array(
        'js_url' => array(
            'lib' => 'http://st.niwenwen.com/jquery.js',
            'form' => 'http://st.niwenwen.com/jquery.form.js',
        ),
        'css_url' => array(
            'lib' => 'http://st.niwenwen.com/mui.css',
            'style' => '/source/front.css',
            'back' => '/source/back.css',
        ),
    );

    /**
     * 数据库配置.
     */
    public static $database = array(
        'host' => 'localhost',
        'user' => 'root',
        'passwd' => 'adqin1001',
        // 'passwd' => '123qwe@wecms',
        'dbname' => 'wecms',
        'charset' => 'utf8',
    );

    /**
     * 路由配置.
     */
    public static $router = array(
        'index' => 'index',
        'login' => 'index',
        'admin' => array(
            'index', 'list', 'add', 'edit', 'setting', 'clean', 'cache'
        ),
    );

}
