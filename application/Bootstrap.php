<?php
/**
 * 定义项目需要函数库.
 * 定义App类.
 * 启动App.
 */

/**
 * 魔术函数， 自动加载需要使用的类.
 * @param string $className ClassName.
 * @return object
 */
function __autoload($className) {
    $filePath = ROOT_PATH . '/application/' . $className . '.php';
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        exit("文件丢失， 类'$className' 不存在！");
    }
}

/**
 * 将字符串使用zlib函数压缩.
 * @param string $string The string to deflate.
 * @return string
 */
function cumpress($string)
{
    return gzdeflate($string, 9);
}

/**
 * 将字符串解压缩.
 * @param string $string The deflate string.
 * @return string
 */
function uncumpress($string)
{
    return gzinflate($string);
}

/**
 * App类定义: 单列模式，主要用于URL解析与路由， 项目配置获取.
 */
class App extends CInstance
{
    public static function start()
    {
        print_r($_SERVER);
    }
}

App::getInstance()->start();