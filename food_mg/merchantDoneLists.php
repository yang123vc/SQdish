<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	//print_r($_REQUEST);exit();
	//echo time();exit;
	$db = new DB();
	$id = $_REQUEST['id'];
	//获取商家详细信息
	$sql = "select sellerName,tel from f_seller where id='$id'";
	$sellerList = $db->get_one($sql);	
	//获取负责人信息
	$sql = "select * from f_user where sid='$id'";
	$userList = $db->get_one($sql);


	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;

	//应结算金额
	$sql = "select sum(all_cost) from f_order where status=1 and sid='$id'";
	$totle_money = $db->get_a($sql);
	$sql = "select sum(money) from f_done where u_id='$id'";
	$rs = $db->get_a($sql);
	if($rs){
		$done_money = $rs; 
	}else{
		$done_money =0;
	}
	//结算列表
	$sql = "select * from f_done where u_id=$id limit $location,$pageSize";
	$doneList = $db->get_all($sql);
	$sql = "select count(*) from f_done where u_id='$id'";
	$count = $db->get_a($sql);
	//订单列表
	$sql = "select * from f_order where sid='$id'";
	$orderList = $db->get_all($sql);
	

	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;

	

	include_once dirname(__FILE__)."/templates/merchantDoneLists.html";
?>