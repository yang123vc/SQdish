<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	
	$db = new DB();
	$sid = $_SESSION[SID];
	$dtid = 0;
	
	$seller = $db->get_one("select * from f_seller where id = $sid");
	$_SESSION["name"] = $seller['sellerName'];

	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	
	$sql = "select * from f_member_coupon where sid = $sid order by status desc, id desc limit $location,$pageSize";
	$couponList = $db->get_all($sql);
	$size_coupon = count($couponList);
	for ($i=0; $i < $size_coupon; $i++) {
		$cname[$i] = $db->get_one("select couponNameCN, couponNameTHAI, pic from f_coupon where id = ".$couponList[$i]['cid']);
		$couponList[$i]['couponname'] = $cname[$i]['couponNameCN'].'('.$cname[$i]['couponNameTHAI'].')';
		$couponList[$i]['pic'] = $cname[$i]['pic'];
		$couponList[$i]['statusname'] = status_to_name($couponList[$i]['status']);
	}
	
	
	$sql = "select count(1) as count from f_member_coupon where sid = $sid";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	include_once dirname(__FILE__)."/templates/CouponStatus.html";

	function status_to_name($status){
		if ($status == 0) {
			$statusname = '未使用';
		}
		elseif ($status == 1) {
			$statusname = '已使用';
		}
		return $statusname;
	}
?>