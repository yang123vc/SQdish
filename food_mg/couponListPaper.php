<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	date_default_timezone_set('PRC');
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
	
	$sql = "select b.id,a.sid,a.couponNameCN,a.couponNameTHAI,a.couponIntroCN,b.usetime,b.code,b.status from f_coupon as a,f_coupon_paper as b where a.sid = $sid and a.status = 0 and b.status <>3 and a.type = 2 and b.cid=a.id order by b.id desc limit $location,$pageSize";
	$couponList = $db->get_all($sql);
	
	$sql = "select count(*) from f_coupon as a,f_coupon_paper as b where a.sid = $sid and a.status = 0 and b.status <>3 and a.type = 2 and b.cid=a.id";
	$count = $db->get_a($sql);
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	include_once dirname(__FILE__)."/templates/CouponListPaper.html";
?>