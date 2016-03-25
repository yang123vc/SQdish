<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	//会员列表
	$db = new DB();
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;

	$sql = "select id,username,nickname,phone,email,reg_time,opt from f_member order by id desc limit $location,$pageSize";
	$MemberList = $db->get_all($sql);

	$sql = "select count(*) as count from f_member";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;


	include_once dirname(__FILE__)."/templates/MemberList.html";

?>