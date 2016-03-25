<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	//订单详情
	$db = new DB();
	$id = $_GET['id']+0;
	$time = $db->get_a("select time from f_dish where id='$id'");
	$sql = "select * from f_order_detail where oid='$id'";
	$OrderDetail = $db->get_all($sql);
	foreach ($OrderDetail as $v) {
		$sql = "select dishNameCN from f_dish where id='$v[did]'";
		$v['dishName'] = $db->get_a($sql);
		$Detail[] = $v;
	}

	include_once dirname(__FILE__)."/templates/OrderDetail.html";
?>