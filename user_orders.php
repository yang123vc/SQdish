<?php
	$isw = 0; // 伪静态配置 1开启 0关闭
	date_default_timezone_set ( 'Asia/Shanghai' );
	include_once dirname ( __FILE__ ) . '/data/db/DB.php';
	include_once dirname ( __FILE__ ) . '/inc/page.class.php';
	include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
	$db = new DB();
	$uid = get_user_info();

	if($uid) {
			$user_info = $db -> get_one("select * from f_member where id=".$uid);
	}else{
			$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
			header("Location:".$jump_url);	
	}
	//var_dump($user_info);exit;
	//分页
	$pageSize = 5;
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	$act = trim($_GET['act']);
	if ($act=='no') {
		//-------获取待评价订单-------//		echo "xxx";

		$sql = "SELECT id,status,time,ordercode,sid FROM f_order WHERE mid = '$uid'  AND is_commment=0 AND is_on=1 AND status=1 OR status=2 ORDER BY id DESC LIMIT $location,$pageSize";
		$order= $db->get_all($sql);
		foreach($order as $v){
			$sql1 = "SELECT sellerName FROM f_seller WHERE id ='$v[sid]'";
			$v['sellerName'] = $db->get_a($sql1);
			$sql2 = "SELECT did,dish_status,goods_price,goods_count FROM f_order_detail WHERE oid='$v[id]'";
			$v['detail'] = $db->get_all($sql2);
			$orders[] = $v;
		}
		
		//分页
		$sql = "SELECT count(*) FROM f_order WHERE mid = '$uid'  AND is_commment=0 AND is_on=1 AND status=1 OR status=2";
		$count = $db->get_a($sql);
			$pages = new page($pageSize,$count,$isw); 
			$pages->set( $ary = array( 'display_str_flag'=>false,
								'prev_label'=>'<<',
								'next_label'=>'>>',
								'last_label'=>'>>>',));	
			$agent = $_SERVER['HTTP_USER_AGENT'];  
	
		if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

			include_once dirname(__FILE__)."/templates/wap/member/account-MyOrder_No.html";

		}else {	

		include_once dirname ( __FILE__ ) . '/templates/member/user_orders_no_comment.html';

		};

		

	}else{
	//-------获取所有订单-------//


	$sql = "SELECT id,status,time,ordercode,sid FROM f_order WHERE mid = '$uid' AND is_on=1 ORDER BY id DESC LIMIT $location,$pageSize";
	$order= $db->get_all($sql);
	$sid = $order['sid'];
	foreach($order as $v){
		$sql1 = "SELECT sellerName FROM f_seller WHERE id ='$v[sid]'";
		$v['sellerName'] = $db->get_a($sql1);
		$sql2 = "SELECT did,dish_status,goods_price,goods_count FROM f_order_detail WHERE oid='$v[id]'";
		$v['detail'] = $db->get_all($sql2);
		$orders[] = $v;
	}
	
	//分页
	$sql = "SELECT count(*) FROM f_order WHERE mid = '$uid' AND is_on=1";
	$count = $db->get_a($sql);
		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));	
			$agent = $_SERVER['HTTP_USER_AGENT'];  
	
		if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

			include_once dirname(__FILE__)."/templates/wap/member/account-MyOrder.html";

		}else {	

		include_once dirname ( __FILE__ ) . '/templates/member/user_orders.html';

		};

   	}


?>