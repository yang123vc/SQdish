<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	$db = new DB();
	$id = $_SESSION['user_sesssion']['id'];//推荐人id
	$key = $_REQUEST['key'];
	$key = replace($key, "'", "\'");
	//分页参数

	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	//获取推荐过的ID
	$sql ="select sid from f_user where sid<>0 and username like '%$key%'";
	$sid = $db->get_all($sql);

	foreach ($sid as $v) {
		$sidList[] = $v['sid'];
	}
	//排除不是推荐人推荐的用户
	foreach ($sidList as $v) {
		$sql = "select id from f_seller where id='$v' and referee='$id'";
		$List[] = $db->get_one($sql);
	}
	$List = array_filter($List);

		//分页参数

	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;	
	//获取用户
	//print_r($List);exit;
	foreach ($List as $v) {
		$sql = "select * from f_user where sid='$v[id]' limit $location,$pageSize";
		$userList[] = $db->get_one($sql);
	}

	$userList = array_filter($userList);
	//print_r($sidList);exit;
	//print_r($userList);exit;
	foreach ($List as $v) {
		$sql = "select count(*) as count from f_user where sid=$v[id]";
		$temp[] = $db->get_a($sql);
	}

	

	$count = array_sum($temp);
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	
	include_once dirname(__FILE__)."/templates/refereeUserList.html";
	
	
?>