<?php
require_once '../App/Config/Db.php';
require_once '../App/Db.php';

$post_id = isset($_GET['post_id']) && $_GET['post_id'] ? $_GET['post_id'] : '';
if (!$post_id) {
    // 错误的post_id
    echo 0;
    exit;
}

$sql = "select count(1) from `post` where `post_id` = '$post_id'";
if (!\Db::instance()->exists($sql)) {
    // post_id不存在
    echo 0;
    exit;
}

$views = 1;

$sql = "select `id`,`views` from `post_view` where `post_id` = '$post_id'";
$row = \Db::instance()->getRow($sql);
if ($row) {
    $id = $row['id'];
    $views = $row['views'] + 1;
    $latest_time = time();
    $ret = \Db::instance()->updateById('post_view', ['views' => $views, 'latest_time' => $latest_time], $id);
    if ($ret !== true) {
        // Db更新错误
        echo 0;
        exit;
    }
} else {
    $param = [
        'post_id' => $post_id,
        'views' => $views,
        'latest_time' => time()
    ];
    $ret = \Db::instance()->insert('post_view', $param);
    if (!$ret || !is_numeric($ret)) {
        // Db更新失败
        echo 0;
        exit;
    }
}

echo $views;
exit;