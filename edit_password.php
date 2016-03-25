<?php
ini_set("display_errors",0);
$isw=0;//伪静态配置  1开启 0关闭
date_default_timezone_set('Asia/Shanghai');
include_once dirname(__FILE__).'/data/db/DB.php';
include_once dirname(__FILE__).'/inc/global.inc.php';
//--------------判断是否登录-------------------//
$user_info = get_user_info();
//--------------判断是否登录-------------------//
if(!empty($user_info)) {
	$action = isset($_GET['action']) ? $_GET['action'] : '';
	
	if($action) {
		switch ($action) {
			case 'phone' : //手机验证修改
				if(!empty($user_info)) {
					$phone = $user_info['phone'];
					if($phone){ //手机已绑定
						include_once dirname(__FILE__)."/templates/edit_verifyphone.html";
					}else{//手机未绑定
						$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/account_security.php?action=bindphone';
						header("Location:".$jump_url);
					}
				}else{//手机未绑定
					$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/account_security.php?action=bindphone';
					header("Location:".$jump_url);
				}				
				break;
			case 'email' : //邮箱验证修改
				if(!empty($user_info)) {
					$email = $user_info['email'];
					if($email) {//有邮箱
						//发送邮件
						$mailtitle = '修改密码验证';
						$mailurl = 'http://'.$_SERVER['HTTP_HOST'].'/edit_password.php?action=verify';
						$mailcontent = '<h1>请点击下面的链接完成密码修改</h1><br><a href="'.$mailurl.'">'.$mailurl.'</a>';
						$state = com_send_email($user_info['email'],$mailtitle,$mailcontent);
						if($state) {
							$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/edit_password.php?action=success';
							header("Location:".$jump_url);
						} else {//邮件发送失败，直接跳到修改密码页面
							include_once dirname(__FILE__)."/templates/edit_password.html";
						}											
					}else{//没有邮箱，先去账号安全处绑定邮箱
						$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/account_security.php?action=bindemail';
						header("Location:".$jump_url);	
					}
				}else{
					$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/account_security.php?id=action=bindemail';
					header("Location:".$jump_url);	
				}
				break;
			case 'success':
				$email = $user_info['email'];
				$success = 1;
				include_once dirname(__FILE__)."/templates/success.html";
				break;
			case 'pwdsuc':
				$success = 2;
				include_once dirname(__FILE__)."/templates/success.html";
				break;
			case 'phonesuc':
				$success = 2;
				include_once dirname(__FILE__)."/templates/success.html";
				break;
			case 'verify':
				include_once dirname(__FILE__)."/templates/edit_verifyemail.html";
				break;
			default:
				include_once dirname(__FILE__)."/templates/edit_password.html";
		}
	} else {
		include_once dirname(__FILE__)."/templates/edit_password.html";
	}
}else{//未登录，直接跳到登录页面
	$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
	header("Location:".$jump_url);
}
?>