<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	//print_r($_SESSION);exit;
	$db = new DB();
	$sid = $_SESSION[SID]=$_SESSION['user_sesssion']['sid'];
	$dtid = 0;
	
	if(isset($_REQUEST['dtid']) && $_REQUEST['dtid']){
		$dtid = $_REQUEST['dtid'];
		$where = "and dtid = $dtid";
	}else{
		$where = "";
	}
	
	$seller = $db->get_one("select * from f_seller where id = $sid");
	$_SESSION["name"] = $seller['sellerName'];

	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	
	$sql = "select * from f_dish_extra where sid=0 order by ser_id desc limit $location,$pageSize";
	$dishList = $db->get_all($sql);
	
	$sql = "select count(*) as count from f_dish_extra where sid =0 $where order by ser_id desc";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	include_once dirname(__FILE__)."/templates/DishExtra.html";
?>