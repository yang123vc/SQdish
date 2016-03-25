<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();
	
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	//获取有订单商家的id
	$sql="select distinct sid from f_order  order by sid desc";
	$id_list = $db->get_all($sql);
	$userList =array();	
	foreach ($id_list as $v) {
		$sql = "select sellerName from f_seller where id ='$v[sid]'";
		$v['sellerName'] = $db->get_a($sql);
		$sql = "select username from f_user where sid='$v[sid]'";
		$v['username'] = $db->get_a($sql);
		$userList[] = $v;
	}



	$count = count($userList);
	$userList =array_slice($userList,$location,$pageSize,true);
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	
	include_once dirname(__FILE__)."/templates/merchantDoneList.html";
	
	
?>