<?php
/**
 * 赞和收藏的操作.
 */
require_once '../App/Config/Db.php';
require_once '../App/Db.php';

$post_id = isset($_GET['post_id']) && $_GET['post_id'] ? $_GET['post_id'] : '';
$openid = isset($_GET['openid']) && $_GET['openid'] ? $_GET['openid'] : '';
// type = 'zan' or type = 'connect'.
$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
// op = 'add' or op = 'del'.
$op = isset($_GET['op']) && $_GET['op'] ? $_GET['op'] : '';

if (!$post_id || !$openid || !$type || !$op) {
    echo '';
    exit;
}

if (!in_array($type, ['zan', 'connect']) || !in_array($op, ['add', 'del'])) {
    echo '';
    exit;
}

$sql = "select count(1) from `post` where `post_id` =  '$post_id'";
if (!\Db::instance()->exists($sql)) {
    // 如果文章不存在.
    echo '';
    exit;
}

// 查询记录是否存在.
$sql = "select `id` from `connect_zan` where `user_id`='$openid' and `post_id`='$post_id'";
$row = \Db::instance()->getRow($sql);

if ($type == 'zan' && $op == 'add') {
    // 点赞
    if ($row) {
        $ret = \Db::instance()->updateById('connect_zan', ['zan' => 1], $row['id']);
        if ($ret === true) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    } else {
        $ret = \Db::instance()->insert('connect_zan', ['user_id' => $openid, 'post_id' => $post_id, 'zan' => 1, 'connect' => 0]);
        if (!$ret || !is_numeric($ret)) {
            echo 0;
        } else {
            echo 1;
        }
        exit;
    }
}

if ($type == 'zan' && $op == 'del') {
    // 取消点赞
    if (!$row) {
        echo 1;
        exit;
    } else {
        $ret = \Db::instance()->updateById('connect_zan', ['zan' => 0], $row['id']);
        if ($ret === false) {
            echo 0;
        } else {
            echo 1;
        }
        exit;
    }
}

if ($type == 'connect' && $op == 'add') {
    // 收藏
    if ($row) {
        $ret = \Db::instance()->updateById('connect_zan', ['connect' => 1], $row['id']);
        if ($ret === true) {
            echo 1;
        } else {
            echo 0;
        }
        exit;
    } else {
        $ret = \Db::instance()->insert('connect_zan', ['user_id' => $openid, 'post_id' => $post_id, 'zan' => 0, 'connect' => 1]);
        if (!$ret || !is_numeric($ret)) {
            echo 0;
        } else {
            echo 1;
        }
        exit;
    }
}

if ($type == 'connect' && $op == 'del') {
    // 取消收藏
    if (!$row) {
        echo 1;
        exit;
    } else {
        $ret = \Db::instance()->updateById('connect_zan', ['connect' => 0], $row['id']);
        if ($ret === false) {
            echo 0;
        } else {
            echo 1;
        }
        exit;
    }
}