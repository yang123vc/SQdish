<?php
ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
    include_once dirname(__FILE__).'/inc/cart.php';
	include_once dirname(__FILE__).'/inc/json_class.php';
	include_once dirname(__FILE__).'/inc/cookie_class.php';
	//$db = new DB();
    $carinfo = JSON::decode(str_replace(array('&','$'),array('"',','),ICookie::get('shoppingcart')));
    $Cart = new Cart();
    
    $carinfo = $Cart->getMyCart();   
  
    
    
    $goods_id = $_POST['goods_id'];
    $shop_id = $_POST['shop_id'];
    $num  = $_POST['num'];
    
    $checkinfo = $Cart->add($goods_id,$num,$shop_id);
    echo json_encode($checkinfo);
   

?>