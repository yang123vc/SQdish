<?php
ini_set("display_errors",0);
$isw = 0; // 伪静态配置 1开启 0关闭
date_default_timezone_set ( 'Asia/Shanghai' );
include_once dirname ( __FILE__ ) . '/data/db/DB.php';
include_once dirname ( __FILE__ ) . '/inc/page.class.php';
include_once dirname ( __FILE__ ) . '/inc/global.inc.php';

$user_info = get_user_info();
$uid = $user_info['id'];
if (!empty($user_info)) {
	$db = new DB ();	

	$pageSize = 10;
	if(isset($_GET['page']) && $_GET['page'] != "") {
		$page = $_GET['page'];
	}else{
		$page = 1;
	}
	$location = ($page-1)*$pageSize;
	$count = count($db -> get_all("select * from f_system_message order by time desc"));
	$msg_rs = $db -> get_all("select * from f_system_message order by time desc limit ".$location.",".$pageSize);
	$pages = new page($pageSize,$count,$isw);
	$pages->set( $ary = array( 'display_str_flag'=>false,
			'prev_label'=>'<<',
			'next_label'=>'>>',
			'last_label'=>'>>>',));
	include_once dirname ( __FILE__ ) . "/templates/user_msg_manage.html";
} else {
	$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
	header("Location:".$jump_url);
}
?>