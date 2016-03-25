<?php
ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
    include_once dirname(__FILE__).'/inc/cart.php';
	include_once dirname(__FILE__).'/inc/json_class.php';
	include_once dirname(__FILE__).'/inc/cookie_class.php';
    $Cart = new Cart();
    $checkinfo = $Cart->clear();
    echo json_encode($checkinfo);
   

?>