<?php

/**
 * 获取我的收藏/点赞总页数，以及初始数据.
 */
require_once '../App/Config/Db.php';
require_once '../App/Db.php';

$openid = isset($_GET['openid']) && $_GET['openid'] ? $_GET['openid'] : '';
if (!$openid) {
    echo '';
    exit;
}

$limit = 12;
$connect_total_page = $zan_total_page = 0;
$connect_list = $zan_list = '';

// 收藏数
$sql = "select count(1) from `connect_zan` where `user_id` = '$openid' and `connect` = 1";
$count = \Db::instance()->count($sql);
if ($count) {
    $connect_total_page = ceil($count / $limit);
    $rs = getPostList('connect', $openid);
    if ($rs) {
        $connect_list = $rs;
    }
}

// 点赞数
$sql = "select count(1) from `connect_zan` where `user_id` = '$openid' and `zan` = 1";
$count = \Db::instance()->count($sql);
if ($count) {
    $zan_total_page = ceil($count / $limit);
    $rs = getPostList('zan', $openid);
    if ($rs) {
        $zan_list = $rs;
    }
}

echo json_encode(['connect_total_page' => $connect_total_page, 'zan_total_page' => $zan_total_page, 'connect_list' => $connect_list, 'zan_list' => $zan_list]);
exit;

// 获取文章列表.
function getPostList($type = 'connect', $openid) {
    if ($type == 'connect') {
        $sql = "select p.`post_id`,p.`title`,p.`author`,p.`image_url`,p.`image_up_time`,p.`description` from `connect_zan` as mix, `post` as p where mix.`user_id` = '$openid' and mix.`connect` = 1 and mix.`post_id` = p.`post_id` order by mix.`id` desc limit 12";
    } else {
        $sql = "select p.`post_id`,p.`title`,p.`author`,p.`image_url`,p.`image_up_time`,p.`description` from `connect_zan` as mix, `post` as p where mix.`user_id` = '$openid' and mix.`zan` = 1 and mix.`post_id` = p.`post_id` order by mix.`id` desc limit 12";
    }

    $rs = \Db::instance()->getList($sql);
    $return = [];
    foreach ($rs as $k => $r) {
        $return[$k] = $r;
        $return[$k]['image_url'] = 'http://st.imeiwen.org/' . $r['image_url'] . '?imageView2/2/w/780/' . $r['image_up_time'];
    }
    return $return;
}
