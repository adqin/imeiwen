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
        // 自动加载.
        self::autoload();
        \Router::route();
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
