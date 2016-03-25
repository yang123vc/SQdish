<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	//print_r($_REQUEST);exit();
	//echo time();exit;
	$db = new DB();
	$id = $_REQUEST['id'];
	//获取商家详细信息
	$sql = "select sellerName,tel from f_seller where id='$id'";
	$sellerList = $db->get_one($sql);	
	//获取负责人信息
	$sql = "select * from f_user where sid='$id'";
	$userList = $db->get_one($sql);


	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;

	//获一周订单数
		$endTime = time();//当前时间戳
		//上一次结算的时间戳
		$sql = "select done_time from f_done where u_id='$id'";
		$startTime = $db->get_a($sql);	
		
		if (!$startTime) {//第一次结算订单
			//获取订单信息
			$sql = "select * from f_order where sid='$id' and status=1 and is_done=0 order by time limit $location,$pageSize";
			$orderList = $db->get_all($sql);
			//订单数
			$sql = "select count(*) from f_order where sid='$id' and status=1 and is_done=0";
			$count = $db->get_a($sql);
		}else if($endTime-$startTime<60*60*24*7){
			$lost =(60*60*24*7-$endTime+$startTime)/3600;
			echo "<script>alert('不在结算时间,请在".intval($lost)."小时后进行结算');</script>";
			echo "<script>location.href='merchantDone.php'</script>";
			exit;
		}else if ($endTime-$startTime>=60*60*24*7) {//第一次以后的一周订单
			//获取订单信息
			$sql = "select * from f_order where sid='$id' and status=1 and is_done=0 time between '$startTime' and '$endTime' order by time limit $location,$pageSize";
			$orderList = $db->get_all($sql);
			//订单数
			$sql = "select count(*) from f_order where sid='$id'  and status=1 and is_done=0 and time between '$startTime' and '$endTime'";
			$count = $db->get_a($sql);
		}	





	
	

	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;

	

	include_once dirname(__FILE__)."/templates/merchantDones_new.html";
?>