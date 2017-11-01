<?php

/**
 * 应用加载.
 * 
 * @author aic <41262633@qq.com>
 */

/**
 * Class App.
 */
class App {

    /**
     * 应用开始.
     */
    public static function start() {
        // 初始化.
        static::init();
        
        // 自动加载.
        static::autoload();
        
        // 路由加载.
        \Router::route();
    }
    
    /**
     * 初始化.
     */
    public static function init() {
        // 需手动创建Caches目录.
        if (!file_exists(CACHE_PATH)) {
            exit('请创建Caches目录');
        }
        
        if (!file_exists(APP_PATH . '/Config/Db.php')) {
            exit('请配置Db');
        }
        
        if (!file_exists(APP_PATH . '/Config/Qiniu.php')) {
            exit('请配置七牛');
        }
    }

    /**
     * 自定义的类文件自动加载.
     */
    public static function autoload() {
        spl_autoload_register(function($class) {
            // 转义反斜线.
            $class = str_replace("\\", "/", $class);
            // 数据格式化.
            $arr = explode('/', $class);
            $r = array();
            foreach ($arr as $a) {
                if ($a) {
                    $r[] = $a;
                }
            }

            // 重新组合.
            $class = implode('/', $r);
            $class_file = APP_PATH . $class . '.php';
            if (!file_exists($class_file)) {
                // 404 not found.
                \Common::noPage();
            }
            require_once $class_file;
        });
    }

}
