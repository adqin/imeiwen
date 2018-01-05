<?php

include_once "wxBizDataCrypt.php";


$appid = 'wx809054e9326721af';
$sessionKey = 'v2mMeZhLmPZydC6z6yWZhQ==';

$encryptedData="C2DKzXoK43dIdKUlm9fz1V330FnbYBSm2T/IXEtNv1LEXTXWzMhV2Xno8mM8jO+TuN/G3bJrd0JJvUID4ylheWdyJhjOHruIWnzZqpolf6Q1dzbc0HS+f8fNJ1fx07gSwIbQMU5kOMN67nxb94Tsc/YAzQWb0gXDEg6sPfOLp1EDtb/D/5STkEy1HPQDcLqSBzTwn9d1rbwWdeSu5kEIm4GEcgiC/uaAcOsqf3qdrBpYpDmY3gLSDK7EZPhmfCbQV6pMp4S6SFouDIY+JG4qf6x8eN9fPDA1vNmXQzkwJ0TtPAKYX6sNKyRSrrZS3TEovMAf2oHPIOedNZ8LdZP+pLpVo1l8a8c+32GADkrjnBLPEkGTGO3lXdrCIJ76sQj0C0x8CtpwlaH6x4JrDuBoC7sqL4OZIqer+24Tb7Aa+5R19htyIhQmTIVGjRP2c8gVVwRnRfF0Kbe1nZFYThg5npQf7/PQZq8D+fmAxT8RCaaQmDOGWvtHe4JmF+Z8G8JV";

$iv = "d9I6b6c+PAUjGNwp/J/X9Q==";

$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data );

if ($errCode == 0) {
    print($data . "\n");
} else {
    print($errCode . "\n");
echo "error";
}
