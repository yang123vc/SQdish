<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	//print_r($_POST);exit();
	//接收数据


	//调用model
	$db = new DB();


	$data = array();
	$data['u_id'] = $id = $_POST['id']+0;//推荐人id
	$data['voucher'] = trim($_POST['pic']); //凭证
	$data['role'] = 3;//推荐人结算
	$data['card'] = $_POST['card'];//开泰银行卡号
	$data['done_time'] = time();//结算时间
	$data['is_done'] = 1;//完成结算
	//验证数据合法性
	if (!is_numeric($id)) {
		echo "<script>alert('ID ERROR');</script>";
		echo "<script>location.href='refereeDone.php'</script>";
		exit;
	}

	if (empty($data['card'])) {
		echo "<script>alert('CARD ERROR');</script>";
		echo "<script>location.href='refereeDone.php'</script>";
		exit;
	}

			//获一周推荐数
		$endTime = time();//当前时间戳
		$sql = "select done_time from f_done where u_id='$id'";
		$startTime = $db->get_a($sql);
		//未结算过

		if (!$startTime) {
			//审核成功数
			$sql = "select count(*) from f_seller where referee='$id'  and is_on=1 and is_done=0";
			$w_success = $db->get_a($sql);
		}else if($endTime-$startTime<60*60*24*7){
			$lost =(60*60*24*7-$endTime+$startTime)/3600;
			echo "<script>alert('不在结算时间,请在".intval($lost)."小时后进行结算');</script>";
			echo "<script>location.href='refereeDone.php';</script>";
			exit;
		}else if ($endTime-$startTime>=60*60*24*7) {
			//审核成功数
			$sql = "select count(*) from f_seller where referee='$id' and is_on=1  and is_done=0 and reg_time between '$startTime' and '$endTime'";
			$w_success = $db->get_a($sql);
		}

		//验证金额是否正确
		$seller = isset($_POST['checkbox'])?$_POST['checkbox']:0;
		if ($seller==0) {
			$money_c=0;
		}else{
			$money_c = count($seller)*30;
		}
		$money_s = $w_success*30;
		if($money_c==0){
			echo "<script>alert('MOENY ERROR');</script>";
			echo "<script>location.href='refereeDone.php'</script>";
			exit;
		}else if($money_c<=$money_s){
			$data['money'] = $money_c;
		}else{
			echo "<script>alert('MOENY ERROR');</script>";
			echo "<script>location.href='refereeDone.php'</script>";
			exit;
		}
		//入库
		foreach ($seller as $v) {
			$sql = "update f_seller set is_done=1 where id='$v'";
			$db->query($sql);
		}
		$rs = $db->insert('f_done',$data);

	//判断是否已结算过
	/*
	$sql = "select count(*) from f_done where u_id='$id' and done_time between '$startTime' and '$endTime'";
	if($db->get_a($sql)){
		$sql = "select w_money from f_done where u_id='$id' and done_time between '$startTime' and '$endTime'";
		$rs = $db->get_a($sql);
		$sql = "select money from f_done where u_id='$id' and done_time between '$startTime' and '$endTime'";
		$rz = $db->get_a($sql);

		$data['money'] = $_POST['money']+$rz;//结算金额
		$data['w_money'] = $_POST['money']+$rs;//周金额

		if ($data['money']==0||empty($data['money'])) {
			echo "<script>alert('MOENY ERROR');</script>";
			echo "<script>location.href='refereeDone.php</script>";
			exit;
		}
		$rs = $db->update("f_done",$data,"u_id='$id'");
	}else{
		$data['money'] = $_POST['money'];//结算金额
		if ($data['money']==0||empty($data['money'])) {
			echo "<script>alert('MOENY ERROR');</script>";
			echo "<script>location.href='refereeDone.php</script>";
			exit;
		}
		$rs = $db->insert('f_done',$data);
	}*/

	

	

	if ($rs) {	

		echo "<script>alert('结算成功');</script>";
		echo "<script>location.href='refereeDone.php'</script>";
	}else{
		echo "<script>alert('结算失败')</script>";
		echo "<script>location.href='refereeDones.php?id=$data[u_id]'</script>";
	}


	//结算入库



?>	