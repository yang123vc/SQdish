<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	$key = $_REQUEST['key'];
	$key = replace($key, "'", "\'");
	$db = new DB();
	
	$sql = "select * from f_user where role=3 and username like '%$key%'";
	$userList = $db->get_all($sql);
	
	$sql = "select count(*) as count from f_user where role=3 and username like '%$key%'";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
	$page = 1;
	$totalPage = 1;
	$start = 1;
	$end = 1;
	$prev = 1;
	$next = 1;
	
	include_once dirname(__FILE__)."/templates/refereeDoneList.html";
?>