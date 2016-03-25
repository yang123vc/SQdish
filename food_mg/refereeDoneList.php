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
	//获取推荐过且审核通过的id
	$sql="select distinct u_id from f_done where is_done=1 and role=3 order by id desc";
	$id_list = $db->get_all($sql);
	$userList = array();
	foreach ($id_list as $v) {
		$sql = "select * from f_user where role=3 and id='$v[u_id]'";
		$userList[] = $db->get_one($sql);
	}
	//print_r($userList);exit;
	$count = count($userList);
	$userList =array_slice($userList,$location,$pageSize,true);
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	
	include_once dirname(__FILE__)."/templates/refereeDoneList.html";
	
	
?>