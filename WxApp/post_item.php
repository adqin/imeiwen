<?php

$post_id = isset($_GET['post_id']) && $_GET['post_id'] ? $_GET['post_id'] : '';
if ($post_id) {
    $cache_file = '../Caches/post/' . $post_id[0] . '/' . $post_id[1] . '/cache.' . $post_id;
    if (file_exists($cache_file)) {
        echo file_get_contents($cache_file);
    }
}
echo '';
exit;

