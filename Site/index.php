<?php
/**
 *  入口文件.
 * 
 * @author aic <41262633@qq.com>
 */

// 设置页面字符编码.
header("Content-type: text/html; charset=utf-8");
// 配置时区.
date_default_timezone_set('Asia/Shanghai');

// 定义网站根目录.
define('ROOT_PATH', realpath(__DIR__ . '/../'));
// 定义网站应用目录.
define('APP_PATH', ROOT_PATH . '/App/');
// 定义网站缓存文件目录.
define('CACHE_PATH', ROOT_PATH . '/Caches/');
// 定义模板目录.
define('TPL_PATH', APP_PATH . 'View/');

require_once ROOT_PATH . '/Vendor/autoload.php';
// 加载应用入口文件.
require_once APP_PATH . 'App.php';
App::start();
