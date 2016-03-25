<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();
	
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']))
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	
	$sql = "select s.*,f.sellerName as name from f_statistics s left join f_seller f on s.sid = f.id order by id desc limit $location,$pageSize";
	$statisticsList = $db->get_all($sql);
	
	$sql = "select count(*) as count from f_statistics";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	include_once dirname(__FILE__)."/templates/DishResultsList.html";
?>