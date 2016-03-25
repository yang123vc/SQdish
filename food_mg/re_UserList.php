<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();
	$id = $_SESSION['user_sesssion']['id'];//推荐人id
	//获取推荐过的ID
	$sql = "select id from f_seller where referee ='$id'";
	$sid = $db->get_all($sql);
	foreach ($sid as $v) {
		$sidList[] = $v['id'];
	}

	//print_r($sidList);exit;
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;//limit $location,$pageSize
	//获取用户
	foreach ($sidList as $v) {
		$sql = "select * from f_user where sid='$v'";
		$userList[] = $db->get_all($sql);
	}
	$userList = array_filter($userList);
	//print_r($userList);exit;
	foreach($userList as $k){
		foreach ($k as $v) {
			$sql = "select sellerName from f_seller where id='$v[sid]'";
		$v['sellerName'] = $db->get_a($sql);
		$user[] =$v;
		}
		
	}
	$userlist = array_slice($user,$location,$pageSize,true);
	//print_r($userlist);exit;


	//print_r($user);exit;
	foreach ($sidList as $v) {
		$sql = "select count(*) as count from f_user where sid=$v";
		$temp[] = $db->get_a($sql);
	}
	//print_r($temp);exit;
	

	$count = array_sum($temp);
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	
	include_once dirname(__FILE__)."/templates/refereeUserList.html";
	
	
?>