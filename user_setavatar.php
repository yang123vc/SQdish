<?php
ini_set("display_errors",0);
$isw=0;//伪静态配置  1开启 0关闭
date_default_timezone_set('Asia/Shanghai');
include_once dirname(__FILE__).'/data/db/DB.php';
include_once dirname ( __FILE__ ) . '/inc/global.inc.php';

//--------------判断是否登录-------------------//
$user_info = get_user_info();
//--------------判断是否登录-------------------//

if(!empty($user_info)) {
	include_once dirname(__FILE__)."/templates/user_setavatar.html";
}else{
	$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
	header("Location:".$jump_url);
}
?>