<?php

require_once '../Vendor/Workerman/Autoloader.php';

use Workerman\Worker;

$worker = new Worker("text://0.0.0.0:5678");

$worker->onMessage = function ($connection, $data) {
    var_dump($data);
    $connection->send("hello world");
};

Worker::runAll();
