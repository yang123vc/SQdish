<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';

	$db = new DB();
	
	$action = "add";
	$sid = $_SESSION[SID];
	
	if(!isset($_SESSION["name"])){
		$seller = $db->get_one("select * from f_seller where id = $sid");
		$_SESSION["name"] = $seller['sellerName'];
	}

	include_once dirname(__FILE__)."/templates/CouponAdd.html";
?>