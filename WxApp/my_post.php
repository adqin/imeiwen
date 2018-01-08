<?php

/**
 * 获取我的收藏/点赞总页数，以及初始数据.
 */
require_once '../App/Config/Db.php';
require_once '../App/Db.php';

$openid = isset($_GET['openid']) && $_GET['openid'] ? $_GET['openid'] : '';
$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 1;
$page = 2;
$type = 'connect';
$openid = 'ogfoT0V91Vcq-omBc2vUvp0sS540';
if (!$openid || !$type) {
    echo '';
    exit;
}

$limit = 12;

if ($type == 'connect') {
    $where = "mix.`user_id` = '$openid' and mix.`connect` = 1 and mix.`post_id` = p.`post_id`";
} else {
    $where = "mix.`user_id` = '$openid' and mix.`zan` = 1 and mix.`post_id` = p.`post_id`";
}
$offset = ($page - 1) * $limit;
$sql = "select p.`post_id`,p.`title`,p.`author`,p.`image_url`,p.`image_up_time`,p.`description` from `connect_zan` as mix, `post` as p  where $where order by mix.`id` desc limit $limit offset $offset";
$rs = \Db::instance()->getList($sql);
$return = [];
foreach ($rs as $k => $r) {
    $return[$k] = $r;
    $return[$k]['image_url'] = 'http://st.imeiwen.org/' . $r['image_url'] . '?imageView2/2/w/780/' . $r['image_up_time'];
}
echo json_encode($return);
exit;
