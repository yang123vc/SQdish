<?php

	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';

	//ajax优惠券清空操作
	$db = new DB();
	$mid = $_POST['mid']+0;
	if (empty($_POST['mid'])) {
		echo "fail";
		exit;
	}
	$sql = "delete from f_coupon_tmp where mid = '$mid'";
	$res = $db->query($sql);
	if ($res) {
		echo "success";
	}else{
		echo "fail";
	}

?>