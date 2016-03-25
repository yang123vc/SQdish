<?php
error_reporting(E_ERROR);
require_once dirname(__FILE__).'/inc/wxpay/phpqrcode/phpqrcode.php';
$url = urldecode($_GET["data"]);
QRcode::png($url);
