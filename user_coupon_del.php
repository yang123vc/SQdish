<?php
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/isLogin.member.php';

	session_start();
	if_login();
	$mid = $_SESSION['user_info']['id'];

	$db = new DB();
	if (isset($_REQUEST['id'])) {
		$id = $_REQUEST['id'];
		$sql = "update f_coupon_tmp set status = 3 where id = $id and mid = $mid";
		$db->query($sql);
		echo "删除成功";
	}
	//未获取到id
	else{
		echo "删除失败";
	}
	
?>