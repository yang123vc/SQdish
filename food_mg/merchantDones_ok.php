<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	//调用model
	$db = new DB();


	$data = array();
	$data['u_id'] = $id = $_POST['id']+0;//商家id
	$data['voucher'] = trim($_POST['pic']); //凭证
	$data['role'] = 2;//商家结算
	$data['card'] = $_POST['card'];//开泰银行卡号
	$data['done_time'] = time();//结算时间
	$data['is_done'] = 1;//完成结算
	//验证数据合法性
	if (!is_numeric($id)) {
		echo "<script>alert('ID ERROR');</script>";
		echo "<script>location.href='merchantDone.php'</script>";
		exit;
	}

	/*if (empty($data['card'])) {
		echo "<script>alert('CARD ERROR');</script>";
		echo "<script>location.href='merchantDone.php'</script>";
		exit;
	}*/

		$endTime = time();//当前时间戳
		$sql = "select done_time from f_done where u_id='$id'";
		$startTime = $db->get_a($sql);
		//未结算过

		if (!$startTime) {
			//获取一周要结算的订单金额
			$sql = "select sum(price) from f_order where sid='$id' and status=1 and is_done=0";
			$money_s = $db->get_a($sql);
		}else if($endTime-$startTime<60*60*24*7){
			$lost =(60*60*24*7-$endTime+$startTime)/3600;
			echo "<script>alert('不在结算时间,请在".intval($lost)."小时后进行结算');</script>";
			echo "<script>location.href='merchantDone.php';</script>";
			exit;
		}else if ($endTime-$startTime>=60*60*24*7) {
			//审核成功数
			$sql = "select sum(price) from f_order where sid='$id' and status=1  and is_done=0 and time between '$startTime' and '$endTime'";
			$money_s = $db->get_a($sql);
		}


		//验证客户端和实际金额是否相等
	           $order = isset($_POST['checkbox'])?$_POST['checkbox']:0;
		if ($order==0) {
			$money_c=0;
		}else{
			$money_c = array_sum($order);
		}
		
		if($money_c==0){
			echo "<script>alert('MOENY ERROR');</script>";
			echo "<script>location.href='merchantDone.php'</script>";
			exit;
		}else if($money_c<=$money_s){
			$data['money'] = $money_c;
		}else{
			echo "<script>alert('MOENY ERROR');</script>";
			echo "<script>location.href='merchantDone.php'</script>";
			exit;
		}
		//修改订单结算状态
		foreach($order as $k=>$v){
			$sql ="update f_order set is_done=1 where id='$k'";
			$db->query($sql);
		}
		//入库完成结算
		$rs = $db->insert('f_done',$data);
		if ($rs) {	

		echo "<script>alert('结算成功');</script>";
		echo "<script>location.href='merchantDone.php'</script>";
	}else{
		echo "<script>alert('结算失败')</script>";
		echo "<script>location.href='merchantDones.php?id=$data[u_id]'</script>";
	}



?>