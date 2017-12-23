<?php

$signature = $GET["signature"];
$timestamp = $GET["timestamp"];
$nonce = $GET["nonce"];
$token = '1234';
$echostr = $GET['echostr'];

$tmpArr = array($timestamp, $nonce, $token);
sort($tmpArr, SORT_STRING);
$tmpStr = implode('', $tmpArr);
$endStr = sha1($tmpStr);

if ($signature == $endStr) {
    echo $echostr;
} else {
    echo '';
}
exit;


