<?php
//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	include_once dirname(__FILE__).'/inc/isLogin.member.php';

	//session_start();
	if_login();
	$_SESSION['user_info']['id'];
	$db = new DB();
	//根据id获取商家信息
	$id=intval($_REQUEST['id']);

	if(empty($id)){
		echo "id err!!";
		echo "<script>location.href='/'</script>";
		exit;
	}
	//获取菜品详细信息
	$sql = "select * from f_member_coupon where c_tmp_id='$id' and status = 0";
	$coupon_now = $db->get_one($sql);
	//print_r($coupon_now);exit;
	//获取优惠券名称等信息
	$cid = $coupon_now['cid'];
	$sql = "select * from f_coupon where id='$cid'";
	$couponDetail = $db->get_one($sql);
	
	
	include_once dirname(__FILE__)."/templates/member/coupon_use.html";
?>