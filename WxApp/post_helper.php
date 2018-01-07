<?php

/**
 * 获取文章的赞与收藏数.
 */
require_once '../App/Config/Db.php';
require_once '../App/Db.php';

$post_id = isset($_GET['post_id']) && $_GET['post_id'] ? $_GET['post_id'] : '';
$openid = isset($_GET['openid']) && $_GET['openid'] ? $_GET['openid'] : '';

if (!$post_id) {
    echo '';
    exit;
}

$connect_num = $zan_num = 0;
$has_connect = $has_zan = 0;

$sql = "select count(1) from `connect_zan` where `post_id` = '$post_id' and `connect` = 1";
$connect_num = \Db::instance()->count($sql);

$sql = "select count(1) from `connect_zan` where `post_id` = '$post_id' and `zan` = 1";
$zan_num = \Db::instance()->count($sql);

if ($openid) {
    $sql = "select `connect`, `zan` from `connect_zan` where `post_id` = '$post_id' and `user_id` = '$openid'";
    $row = \Db::instance()->getRow($sql);
    if ($row && $row['connect']) {
        $has_connect = 1;
    }
    if ($row && $row['zan']) {
        $has_zan = 1;
    }
}

echo json_encode(['connect_num' => $connect_num, 'zan_num' => $zan_num, 'has_connect' => $has_connect, 'has_zan' => $has_zan]);
exit;