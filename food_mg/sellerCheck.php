<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();	//搜索参数	
	
	$sid = $_GET['id']+0;
	$page = $_GET['page']+0;
	//根据id获取详细信息
	$sql = "select * from f_seller where id='$sid'";
	$sellerList = $db->get_one($sql);
	//获取推荐人用户名
	$sql = "select username from f_user where id='$sellerList[referee]'";
	$referee = $db->get_a($sql);
	$sql = "select * from f_user where sid='$sid'";
	$userList = $db->get_one($sql);
	//print_r($sellerList);exit;

		//获取图标
	$sql ="select ser_id,ser_name from f_dish_extra where is_on=1 and sid=0";
	$extra = $db->get_all($sql);	
	//print_r($extra);是否开启
	$extraList = array();
	foreach ($extra as $v) {
		$sql = "select is_on from f_dish_extra where ser_name='$v[ser_name]' and sid='$sid'";
		$v['status'] =$db->get_a($sql);
		$extraList[] = $v;
	}
	//print_r($extraList);exit;
	$sql = "select rt_id,rt_name from f_dish_rtype where is_on=1 and sid=0";
	$rtype =  $db->get_all($sql);
	$rtypeList = array();
	foreach ($rtype as $v) {
		$sql = "select is_on from f_dish_rtype where rt_name='$v[rt_name]' and sid='$sid'";
		$v['status'] =$db->get_a($sql);
		$rtypeList[] = $v;
	}
	include_once dirname(__FILE__)."/templates/sellerCheck.html";
?>