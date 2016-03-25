<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';

	$db = new DB();
	//print_r($_GET);EXIT;
	$rt_id = $_GET['rt_id']+0;	
	$data['sid'] = $sid = $_SESSION[SID];
	if(isset($_REQUEST['dtid']) && $_REQUEST['dtid'])
		$dtid = $_REQUEST['dtid'];
	
	if(!isset($_SESSION["name"])){
		$seller = $db->get_one("select * from f_seller where id = $sid");
		$_SESSION["name"] = $seller['sellerName'];
	}
	
	$sql = "select * from f_dish_rtype where rt_id= $rt_id";
	$list = $db->get_all($sql);
	$sql = "select * from f_dish_options_config";	$options  = $db->get_all($sql);
	include_once dirname(__FILE__)."/templates/restaurantTypeEdit.html";
?>