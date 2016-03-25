<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	$db = new DB();
	$id = $_GET['id']+0;

		$sql = "select username from f_user where id='$id'";
	
		$name = $db->get_a($sql);
		$sql = "select * from f_user where id='$id'";
		$list = $db->get_all($sql);
		//获取总推荐数
		$sql = "select count(*) from f_seller where referee='$id'";
		$sum = $db->get_a($sql);
		//正在审核数
		$sql = "select count(*) from f_seller where referee='$id' and is_on=0";
		$checking = $db->get_a($sql);
		//审核成功数
		$sql = "select count(*) from f_seller where referee='$id'  and is_on=1";
		$success = $db->get_a($sql);

		//应结算金额
		$one = 30.00;
		$totle_money = $success * $one; 
		//已结算金额
		$sql = "select sum(money) from f_done where u_id = '$id' and is_done =1";
		$done_money = $db->get_a($sql);
		if (empty($done_money)) {
			$done_money = 0;
		} 	
		//获一周推荐数
		$endTime = time();//当前时间戳
		$sql = "select done_time from f_done where u_id='$id'";
		$startTime = $db->get_a($sql);
		//未结算过

		if (!$startTime) {
			$sql = "select count(*) from f_seller where referee='$id'";
			$w_sum = $db->get_a($sql);
			//正在审核数
			$sql = "select count(*) from f_seller where referee='$id' and is_on=0";
			$w_checking = $db->get_a($sql);
			//审核成功数
			$sql = "select count(*) from f_seller where referee='$id'  and is_on=1";
			$w_success = $db->get_a($sql);
		}else if($endTime-$startTime<60*60*24*7){
			$sql = "select count(*) from f_seller where referee='$id'";
			$w_sum = $db->get_a($sql);
			//正在审核数
			$sql = "select count(*) from f_seller where referee='$id' and is_on=0";
			$w_checking = $db->get_a($sql);
			//审核成功数
			$sql = "select count(*) from f_seller where referee='$id'  and is_on=1";
			$w_success = $db->get_a($sql);
		}else if ($endTime-$startTime>=60*60*24*7) {
			$sql = "select count(*) from f_seller where referee='$id' and reg_time between '$startTime' and '$endTime'";
			$w_sum = $db->get_a($sql);
			//正在审核数
			$sql = "select count(*) from f_seller where referee='$id' and is_on=0 and reg_time between '$startTime' and '$endTime'";
			$w_checking = $db->get_a($sql);
			//审核成功数
			$sql = "select count(*) from f_seller where referee='$id'  and is_on=1 and reg_time between '$startTime' and '$endTime'";
			$w_success = $db->get_a($sql);
		}	
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	

	$sql ="select * from f_done where u_id='$id' order by id desc limit $location,$pageSize";
	$done= $db->get_all($sql);
	$doneList = array();
	foreach ($done as $v) {
		$sql ="select * from f_user where id='$v[u_id]'";
		$v['user'] = $db->get_one($sql);
		$sql = "select sellerName,path from f_seller where referee='$v[u_id]' and is_done=1";
		$v['seller'] =$db->get_all($sql);
	
		$doneList[] = $v;
	}
	//print_r($doneList);exit;
	
	
	
	$csql = "select count(*) as count from f_done where u_id='$id'";
	//餐厅管理员 组装

	
	$temp = $db->get_one($csql);
	$count = $temp['count'];
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	



	include_once dirname(__FILE__)."/templates/doneCenter.html";
?>