<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';

	$key = $_REQUEST['key'];
	$key = replace($key, "'", "\'");
	$sid = $_SESSION[SID];
	
	$db = new DB();
	
	
	
	$sql = "select * from f_dish where (dishNameCN like '%$key%' or dishNameTHAI like '%$key%') and sid = $sid";
	$dishList = $db->get_all($sql);
	
	$sql = "select count(*) as count from f_dish where (dishNameCN like '%$key%' or dishNameTHAI like '%$key%') and sid = $sid";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
	$page = 1;
	$totalPage = 1;
	$start = 1;
	$end = 1;
	$prev = 1;
	$next = 1;
	
	
	include_once dirname(__FILE__)."/templates/DishList.html";
?>