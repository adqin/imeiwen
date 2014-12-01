<?php
// 魔术函数， 自动加载需要使用的类.
function __autoload($className) {
    $filePath = ROOT_PATH . '/application/' . $className . '.php';
    if (file_exists($filePath)) {
        require $filePath;
    } else {
        exit("文件丢失， 类'$className' 不存在！");
    }
}

// 程序开始执行.
App::getInstance()->start();