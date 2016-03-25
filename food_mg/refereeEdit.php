<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	//根据ID获取信息
	$id = $_GET['id']+0;
	$sql = "select * from f_user where id='$id'";
	$db = new DB();
	$list = $db->get_all($sql);
	foreach ($list as $v) {
		$username = $v['username'];
		$password = $v['password'];
		$firstName = $v['firstName'];
		$surname = $v['surname'];
		$email = $v['email'];
		$phone = $v['phone'];
		$city = $v['city'];
		$card = $v['card'];
	}
	
	include_once dirname(__FILE__)."/templates/refereeEdit.html";
?>