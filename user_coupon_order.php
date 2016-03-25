<?php
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/page.class.php';
	include_once dirname(__FILE__).'/inc/isLogin.member.php';

	session_start();
	if_login();
	$mid = $_SESSION['user_info']['id'];
	$db = new DB();

	//单张优惠券订单确认
	$id = $_GET['id'];
	if (!empty($id)) {
		$sql = "select * from f_coupon_tmp where id = $id and mid = $mid";
		$couponOrder = $db->get_one($sql);

		//获取优惠券详细信息
		$cid = $couponOrder['cid'];
		$sql = "select couponNameCN,pic from f_coupon where id = $cid";
		$coupon_now = $db->get_one($sql);
		$couponOrder['couponNameCN'] = $coupon_now['couponNameCN'];
		$couponOrder['pic'] = $coupon_now['pic'];# code...
		$total = $couponOrder['price']; 
		//获取商家信息

		$sid = $couponOrder['sid'];
		$sql = "select sellerName,pic from f_seller where id = $sid";
		$seller_now = $db->get_one($sql);
		$sellerName = $seller_now['sellerName'];
		$couponOrder_id[] = $id;
	}else{
		$sid = $_GET['sid']+0;
		$sql = "select * from f_coupon_tmp where  mid = $mid and sid = '$sid' and status = 0";
		$list = $db->get_all($sql);
		//订单价格
		$total = 0;
		foreach ($list as $key => $value) {
			$value['couponNameCN'] = $db->get_a("select couponNameCN from f_coupon where id = '$value[cid]'");
			$value['pic'] = $db->get_a("select pic from f_coupon where id = '$value[cid]'");
			$couponOrder[] = $value;
			$total +=$value['price']; 
			$couponOrder_id[] = $value['id'];
		}
		$sql = "select sellerName,pic from f_seller where id = $sid";
		$seller_now = $db->get_one($sql);
		$sellerName = $seller_now['sellerName'];

		//print_r($couponOrder);exit;
	}





	include_once dirname(__FILE__)."/templates/member/coupon_order.html";
?>