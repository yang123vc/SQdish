<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	
	$db = new DB();
	
	$id = $_REQUEST['id'];
	$sql = "select * from f_user where id = $id";
	$user = $db->get_one($sql);
	
	$action = "update";
	
	if($user){
		$username = $user['username'];
		$role = $user['role'];
		$password = $user['password'];
		$sid = $user['sid'] == 0 ? '':$user['sid'];
		$firstName = $user['firstName'];
		$surname = $user['surname'];
		$position = $user['position'];
		$birthday = $user['birthday'];
		$email = $user['email'];
		$phone = $user['phone'];
		$city = $user['city'];
	}
	
	if($_SESSION[USERSESSION]['role']==3){
		$referee = $_SESSION[USERSESSION]['id'];//推荐人id
		$sql = "select id,sellerName from f_seller where referee='$referee'";
		$list = $db->get_all($sql);
		include_once dirname(__FILE__)."/templates/refereeUserAdd.html";
	}else{
		include_once dirname(__FILE__)."/templates/UserAdd.html";
	}	
?>