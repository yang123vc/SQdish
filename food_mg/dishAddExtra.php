<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';

	$db = new DB();	
	$data['sid'] = $sid = $_SESSION[SID];
	if(isset($_REQUEST['dtid']) && $_REQUEST['dtid'])
		$dtid = $_REQUEST['dtid'];
	
	if(!isset($_SESSION["name"])){
		$seller = $db->get_one("select * from f_seller where id = $sid");
		$_SESSION["name"] = $seller['sellerName'];
	}
	
	$sql = "select * from f_dish_type where sid = $sid";
	$dishType = $db->get_all($sql);
	$sql = "select * from f_dish_options_config";	$options  = $db->get_all($sql);
	include_once dirname(__FILE__)."/templates/DishExtraAdd.html";
?>