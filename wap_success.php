<?php
	$isw = 0; // 伪静态配置 1开启 0关闭
	date_default_timezone_set ( 'Asia/Shanghai' );
	include_once dirname ( __FILE__ ) . '/data/db/DB.php';
	include_once dirname ( __FILE__ ) . '/inc/page.class.php';
	include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
	$db = new DB();
	$no = $_GET['no']+0;
	$sql = "select * from f_order where ordercode='$no'";
	$orders = $db->get_one($sql);

	$sql = "select * from f_order_detail where oid='$orders[id]'";
	$detail = $db->get_all($sql);
		

	$agent = $_SERVER['HTTP_USER_AGENT'];  
	
		if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {
				include_once dirname(__FILE__)."/templates/wap/member/success.html";
		}else {	

		

		};

?>