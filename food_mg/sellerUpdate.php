<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	
	$db = new DB();
	
	$sql = "select * from f_user where role = 3";
	$referees  = $db->get_all($sql);
	//接收ID
	$id = $_REQUEST['id'];
	$page = $_REQUEST['page'];
	//查询额外服务
	$sql ="select ser_id,ser_name from f_dish_extra where is_on=1 and sid=0";
	$extra = $db->get_all($sql);	
	//print_r($extra);是否开启
	$extraList = array();
	foreach ($extra as $v) {
		$sql = "select is_on from f_dish_extra where ser_name='$v[ser_name]' and sid='$id'";
		$v['status'] =$db->get_a($sql);
		$extraList[] = $v;
	}
	//print_r($extraList);exit;
	$sql = "select rt_id,rt_name from f_dish_rtype where is_on=1 and sid=0";
	$rtype =  $db->get_all($sql);
	$rtypeList = array();
	foreach ($rtype as $v) {
		$sql = "select is_on from f_dish_rtype where rt_name='$v[rt_name]' and sid='$id'";
		$v['status'] =$db->get_a($sql);
		$rtypeList[] = $v;
	}

	

	$pic = "http://amui.qiniudn.com/bw-2014-06-19.jpg?imageView/1/w/1000/h/1000/q/80";
	$sql = "select * from f_seller where id = $id";
	$seller = $db->get_one($sql);
	
	$action = "update";
	
	if($seller){
		$sellerName = $seller['sellerName'];
		$sellerNameCN = $seller['sellerNameCN'];
		$sellerNameTHAI = $seller['sellerNameTHAI'];
		$is_rec = $seller['is_rec'];
		$online_pay = $seller['online_pay'];
		$coordinates = $seller['coordinates'];
		$address = $seller['address'];
		$contacts = $seller['contacts'];
		$tel = $seller['tel'];
		$intro = $seller['intro'];
		$pic = $seller['pic'];
		$path = $seller['path'];
		$referee = $seller['referee'];
		$country = $seller['country'];		
		$state = $seller['state'];		
		$city = $seller['city'];		
		$region = $seller['region'];
		$BH = $seller['BH'];
		$QRcode_sum = $seller['QRcode_sum'];
		$mer_address = $seller['mer_address'];
	}

	
	if($_SESSION[USERSESSION]['role']==3){
		//获取用户信息
		$sql = "select * from f_user where sid ='$id'";
		$user = $db->get_one($sql);
		if ($user) {
			$uid = $user['id'];
			$uname = $user['username'];
			$surname = $user['surname'];
			$firstName = $user['firstName'];
			$email = $user['email'];
			$phone = $user['phone'];
			$birthday = $user['birthday'];
		}
		include_once dirname(__FILE__)."/templates/refereeSellerAdd.html";
	}else{
		include_once dirname(__FILE__)."/templates/SellerAdd.html";
	}	

?>