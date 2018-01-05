<?php

$page = isset($_GET['page']) && $_GET['page'] ? $_GET['page'] : 1;
$cache_file = '../Caches/wx/post.list.' . $page;

if (file_exists($cache_file)) {
    echo file_get_contents($cache_file);
} else {
    echo '';
}
exit;
