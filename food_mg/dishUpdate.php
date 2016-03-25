<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	
	$db = new DB();
	$id = $_REQUEST['id'];
	$sql = "select * from f_dish where id = $id";
	$dish = $db->get_one($sql);
	$action = "update";
	
	if($dish){
		$sid = $dish['sid'];		$_SESSION[SID] = $sid; 				$seller = $db->get_one("select * from f_seller where id = $sid");		$_SESSION["name"] = $seller['sellerName'];
		$dtid = $dish['dtid'];
		$dishNameCN = $dish['dishNameCN'];
		$dishNameTHAI = $dish['dishNameTHAI'];
		$dishIntroCN = $dish['dishIntroCN'];
		$dishIntroTHAI = $dish['dishIntroTHAI'];
		$pic = $dish['pic'];
		$price = $dish['price'];
		$recommend = $dish['recommend'];
		
		$sql = "select * from f_dish_type where sid = $sid";
		$dishType = $db->get_all($sql);
		
		$sql = "select * from f_dish_options where did = $id order by id";
		$dishOptions = $db->get_all($sql);
	}
	
	$sql = "select * from f_dish_options_config";
	$options  = $db->get_all($sql);
	
	include_once dirname(__FILE__)."/templates/DishAdd.html";
?>