<?php
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/page.class.php';
	include_once dirname(__FILE__).'/inc/isLogin.member.php';
	//print_r($_SESSION);exit();
	if_login();
	$mid = $_SESSION['user_info']['id'];
	$db = new DB();	
	$user_info = $db -> get_one("select * from f_member where id=".$mid);
	$pageSize = 10;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;

	//优惠券数量
	$sql = "select count(1) as num from f_coupon_tmp where mid = $mid and (status = 0 or status = 1)";
	$count_arr = $db->get_one($sql);
	$count = $count_arr['num'];
	//当页数据分页
	$sql = "select * from f_coupon_tmp where mid = $mid and (status = 0 or status = 1) order by status, id desc limit $location,$pageSize";
	$couponList = $db->get_all($sql);
	$size_list = count($couponList);
	//当前日期
	$date = date('Y-m-d',time());

	for ($i=0; $i < $size_list; $i++) { 

		$cid = $couponList[$i]['cid'];
		$sql = "select id,couponNameCN,pic,price,deadline from f_coupon where id = $cid";
		$couponList[$i]['couponinfor'] = $db->get_one($sql);//获取优惠券信息
		
		$sql = "select id,sid,couponNameCN,pic,price,deadline from f_coupon where id = $cid and deadline>$date";
		$couponList_deadline[$i]['couponinfor'] = $db->get_one($sql);//获取优惠券信息——过期

		if ($couponList_deadline[$i]['couponinfor']) {
			$sid = $couponList_deadline[$i]['couponinfor']['sid'];
			$sql = "select sellerName,pic from f_seller where id = $sid";
			$couponList_deadline[$i]['sellerinfor'] = $db->get_one($sql);//获取商家信息--过期

		}
		$sid = $couponList[$i]['sid'];
		$sql = "select sellerName,pic from f_seller where id = $sid";
		$couponList[$i]['sellerinfor'] = $db->get_one($sql);//获取商家信息

		
		$couponList[$i]['time'] = substr($couponList[$i]['time'], 0, 10);
		if ($couponList[$i]['status'] == 0) {
			$couponList[$i]['statusName'] = '等待买家付款';
		}
		elseif ($couponList[$i]['status'] == 1) {
			$couponList[$i]['statusName'] = '请到店使用';
		}
		
	}


	
	$pages = new page($pageSize,$count,$isw);
	$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
	$agent = $_SERVER['HTTP_USER_AGENT'];  
	
	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

		include_once dirname(__FILE__)."/templates/wap/member/account-MyCoupon.html";

	}else {	

		include_once dirname(__FILE__)."/templates/member/userCoupon.html";

	};

	
?>