<?php

require '../vendor/autoload.php';

use Medoo\Medoo;

$db = new Medoo([
    'database_type' => 'mysql',
    'database_name' => 'wecms',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'adqin1001',
    'charset' => 'utf8',
]);

$data = $db->select('meiwen', '*');
echo '<pre>';
print_r($data);