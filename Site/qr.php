<?php

/**
 * 生成二维码.
 */
include '../ThreeLib/phpqrcode/qrlib.php';
$url = $_GET['url'];
$url = strip_tags($url);
$url = trim($url);
$url = addslashes($url);
$url = $url ? $url : 'http://imeiwen.org';
QRcode::png($url, false, QR_ECLEVEL_L, 5);
