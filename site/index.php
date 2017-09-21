<?php
/**
 *  单入口文件.
 */

// 设置页面字符编码.
header("Content-type: text/html; charset=utf-8");
// 配置时区.
date_default_timezone_set('Asia/Shanghai');

// 定义网站根目录.
define('ROOT_PATH', realpath(__DIR__ . '/../'));
// 定义网站应用文件目录.
define('APP_PATH', ROOT_PATH . '/application/');
// 定义网站缓存文件目录.
define('CACHE_PATH', ROOT_PATH . '/cache/');

// 加载应用入口文件.
require APP_PATH . 'App.php';
App::start();
