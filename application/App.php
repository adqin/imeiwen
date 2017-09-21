<?php
/**
 * app应用加载与路由.
 */

class App {

    /**
     * 文件加载与路由.
     */
    public static function start() {
        // 自动加载类文件.
        spl_autoload_register(function($class) {
            static::autoload($class);
        });

        $router = new Router();
        $router->route();
    }

    /**
     * 自动加载类文件.
     */
    private static function autoload($class) {
        // 加载core目录文件.
        $filename = APP_PATH . 'core/' . $class . '.php';
        if (file_exists($filename)) {
            require_once $filename;
        }
        
        // 加载config目录文件.
        $filename = APP_PATH . 'config/' . $class . '.php';
        if (file_exists($filename)) {
            require_once $filename;
        }
    }

}
