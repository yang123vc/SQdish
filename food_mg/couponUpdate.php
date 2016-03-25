<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	
	$db = new DB();
	$id = $_REQUEST['id'];
	$sql = "select * from f_coupon where id = $id";
	$coupon = $db->get_one($sql);
	$action = "update";
	
	if($coupon){
		$sid = $coupon['sid'];
		$_SESSION[SID] = $sid;
		$seller = $db->get_one("select * from f_seller where id = $sid");
		$_SESSION["name"] = $seller['sellerName'];
		$couponNameCN = $coupon['couponNameCN'];
		$couponNameTHAI = $coupon['couponNameTHAI'];
		$couponIntroCN = $coupon['couponIntroCN'];
		$couponIntroTHAI = $coupon['couponIntroTHAI'];
		$pic = $coupon['pic'];
		$price = $coupon['price'];
	}
	
	include_once dirname(__FILE__)."/templates/CouponAdd.html";
?>