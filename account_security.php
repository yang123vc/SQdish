<?php
ini_set("display_errors",0);
include_once dirname(__FILE__).'/data/db/DB.php';
include_once dirname(__FILE__).'/inc/global.inc.php';
//--------------判断是否登录-------------------//
$user_info = get_user_info();
$uid = $user_info['id'];
//--------------判断是否登录-------------------//
$db = new DB();
@$action = $_GET['action'];
if(!empty($user_info)) {   
    if($action == 'bindemail') {
    	if($user_info['verify'] == 1) {
    		$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/account_security.php';
    		header("Location:".$jump_url);
    	}else{
    		$phone = $user_info['phone'];
    		if($phone) {
    			include_once dirname(__FILE__)."/templates/account_bindemail.html";
    		} else {//没有绑定手机，先绑定手机验证身份
    			header("Content-Type:text/html;Charset=utf-8");
    			echo "<script>alert('先绑定手机验证身份');location.href='/account_security.php?action=bindphone'</script>";
    			exit;
    		}
    	}	
    } else if($action == 'verifyemail') {//绑定邮箱，发送成功和邮箱验证
    	$id = $_GET['id'];
    	$email = $_GET['email'];
    	$token = $_GET['token'];
    	if ($token == $_SESSION['verifyemail'] && $id && $email) {
    		//更新用户信息
    		$db -> update('f_member', array('email'=>$email,'verify'=>1), 'id = '.$uid);
    		include_once dirname(__FILE__)."/templates/account_bindemailsuc.html";
    		exit;
    	}else{
    		include_once dirname(__FILE__)."/templates/account_security.html";
    		exit;
    	}
    } else if($action == 'bindphone') {
    	include_once dirname(__FILE__)."/templates/account_bindphone.html";
    	exit;
    } else if($action == 'updatephone') {
    	include_once dirname(__FILE__)."/templates/account_updatephone.html";
    	exit;
    } else if($action == 'unbindphone') {
    	include_once dirname(__FILE__)."/templates/account_unbindphone.html";
    	exit;
    } else if($action == 'bindsuccess') {
    	$success = 3;
    	$phone = $user_info['phone'];
    	include_once dirname(__FILE__)."/templates/success.html";
    } else if($action == 'updatesuccess') {
    	$success = 4;
    	$phone = $user_info['phone'];
    	include_once dirname(__FILE__)."/templates/success.html";
    } else if($action == 'unbindsuccess') {
    	$success = 5;
    	include_once dirname(__FILE__)."/templates/success.html";
    } else {
    	$phone = $user_info['phone'];
    	$email = $user_info['email'];
    	include_once dirname(__FILE__)."/templates/account_security.html";
    }
}else{
    //直接跳到登录页面
    //如果action = verifyemail 登录后直接跳转套邮箱验证 完成验证页面
	$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
	header("Location:".$jump_url);
}
?>