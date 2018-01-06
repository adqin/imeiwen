<?php
$cache_file = '../Caches/wx/tui.list';
if (file_exists($cache_file)) {
    echo file_get_contents($cache_file);
} else {
    echo '';
}
exit;
