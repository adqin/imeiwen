<?php
$cache_file = '../Caches/wx/total.page';
if (!file_exists($cache_file)) {
    echo 0;
    exit;
}

$return = file_get_contents($cache_file);
echo $return;
exit;
