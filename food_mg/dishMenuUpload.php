<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';

	//dish 依赖
	$db = new DB();
	$sid = $_SESSION[SID];
	
	$sql = "select * from f_seller where id = $sid";
	$seller = $db->get_one($sql);
	$dishPic = $seller['dishPic'];
	
	include_once dirname(__FILE__)."/templates/DishMenuUpload.html";
?>