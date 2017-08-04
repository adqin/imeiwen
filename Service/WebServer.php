<?php

define('ROOT_PATH', realpath(__DIR__ . "/../"));
require_once ROOT_PATH . '/Workerman/Autoloader.php';

$server = new \Workerman\WebServer('http://0.0.0.0:80');
$server->addRoot('www.niwenwen.com', ROOT_PATH . '/Sites/');
$server->addRoot('niwenwen.com', ROOT_PATH . '/Sites/wen/');
$server->addRoot('adm.niwenwen.com', ROOT_PATH . '/Sites/admin/');
$server->count = 8;

\Workerman\Worker::runAll();
