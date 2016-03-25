<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$where=" where 1=1 ";
	$db = new DB();	





	
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	//判断需要结算的商家
	/*
		//1.在订单表中有在线支付的商家。
		//2.上次结算时间到本次结算超过一周。

	*/
	$sql = "select distinct sid from f_order where status = 1";
	$sidList = $db->get_all($sql);
	$sellerList = array();
	foreach ($sidList as $v) {
		$sql = "select sellerName from f_seller where id='$v[sid]'";
		$v['sellerName'] = $db->get_a($sql);
		$sql = "select username from f_user where sid='$v[sid]'";
		$v['username'] = $db->get_a($sql);
		$sellerList[] = $v;
	}

	$count = count($sellerList);
	$sellerList= array_slice($sellerList,$location,$pageSize,true);
	
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	require("page.class.php");
	$pages = new page($pageSize,$count); 
	$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
							
							
	
	include_once dirname(__FILE__)."/templates/merchantDone.html";
?>