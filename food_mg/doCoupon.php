<?php

	include_once dirname(__FILE__).'/../data/db/DB.php';

	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	date_default_timezone_set('PRC');
	//print_r($_POST);exit();

	//dish 依赖

	$db = new DB();



	if (isset($_REQUEST['id'])) {

		$id = $_REQUEST['id'];

	}

	else{}//未获取到优惠券id

	$action = $_REQUEST['action'];

	//删除(将优惠券状态更新为已删除)
	
	if($action == "del"){
		$ids = $_GET['ids'];
		//1删除电子；2删除纸质
		$type = isset($_GET['type'])?$_GET['type']:1;
		//$sql = "delete from f_coupon where id = $id";
		if ($type==2) {
			//批量删除
			if ($ids) {
				$id_list = explode(",", $ids);
				$data['status'] = 3;				
				foreach ($id_list as $key => $value) {
					$where = "id = '$value'";
					$db->update('f_coupon_paper',$data,$where);
				}
				echo "<script>location.href='couponListPaper.php'</script>";
				exit;
			}else{
				$sql = "update f_coupon_paper set status = 3 where id = $id";
				$db->query($sql);
				echo "<script>location.href='couponListPaper.php'</script>";
				exit;
			}

		}else{
			//批量删除
			if ($ids) {
				$id_list = explode(",", $ids);
				$data['status'] = 1;			
				foreach ($id_list as $key => $value) {
					$where = "id = '$value'";
					$db->update('f_coupon',$data,$where);	
				}
				echo "<script>location.href='couponList.php'</script>";
				exit;
			}else{
			//删除单个
				$sql = "update f_coupon set status = 1 where id = $id";
				$db->query($sql);
				echo "<script>location.href='couponList.php'</script>";
				exit;	
			}

		}


	}

	elseif ($action == "use") {

		$sid = $_REQUEST[SID];	if(!$sid)		exit;
		$type = $_REQUEST['type']+0;
		$couponcode = trim($_REQUEST['couponcode']);
		if ($type==1) {
			$ifcoupon = $db->get_one("select count(1) as num, cid from f_member_coupon where sid = $sid and couponcode= '$couponcode'");

		//存在对应的优惠券验证码

			if ($ifcoupon['num'] == 1) {

				/*$cid = $ifcoupon['cid'];

				$coupon_deadline = $db->get_one("select deadline from f_coupon where id = $cid");

				$time_now = time();

				$deadline_time = strtotime("".$coupon_deadline['deadline']." +1 day");

				//优惠券过期

				if ($time_now > $deadline_time) {

					$msg = $coupon_overdue;

					echo "<script>location.href='couponUse.php?msg=$msg'</script>";

					exit;

				}

				//未过期

				else {*/

					$usetime = date("Y-m-d H:m:s");

					$sql = "update f_member_coupon set status = 1, usetime = '$usetime' where sid = $sid and couponcode= '$couponcode'";

					$db->query($sql);

					$coupon_now = $db->get_one("select c_tmp_id from f_member_coupon where sid = $sid and couponcode= '$couponcode'");

					$c_tmp_id = $coupon_now['c_tmp_id'];

					$sql = "update f_coupon_tmp set status = 2 where id = $c_tmp_id";

					$db->query($sql);

					echo "<script>location.href='couponStatus.php'</script>";

					exit;

				/*}*/

			}

			//不存在对应的优惠券验证码

			else{

				$msg = $coupon_fail;

				echo "<script>location.href='couponUse.php?msg=$msg'</script>";

				exit;

			}
		}else{
			//纸质优惠券
			$coupon_id = $db->get_a("select id from f_coupon_paper where sid = $sid and code= '$couponcode' and status = 0");
			if ($coupon_id) {
				$data = array();
				$data['usetime'] = time();
				$data['status'] = 1;
				$where = "id = '$coupon_id'";
				$db->update('f_coupon_paper',$data,$where);
				echo "<script>alert('".$coupon_submit_success."');</script>";
				echo "<script>location.href='couponListPaper.php'</script>";
				exit;
			}else{
				$msg = $coupon_fail;

				echo "<script>location.href='couponUse.php?msg=$msg'</script>";

				exit;
			}
		}


	}

	else{

		$sid = $_REQUEST[SID];	if(!$sid)		exit;



		$couponNameCN = $_REQUEST['couponNameCN'];

		$couponNameTHAI = $_REQUEST['couponNameTHAI'];

		$couponIntroCN = $_REQUEST['couponIntroCN'];

		$couponIntroTHAI = $_REQUEST['couponIntroTHAI'];

		$pic = $_REQUEST['pic'];


		$price = empty($_REQUEST['price'])?0:$_REQUEST['price'];

		//接收并判断纸质还是电子，电子默认1
		$amount = empty($_REQUEST['num'])?0:$_REQUEST['num'];
		$type = $_POST['role']+0;
		$addtime = time();
		//新增

		if($action == "add"){

			$sql = "insert into f_coupon(sid,price,couponNameCN,couponNameTHAI,couponIntroCN,couponIntroTHAI,pic,amount,type,addtime) ".

				"values($sid,$price,'$couponNameCN','$couponNameTHAI','$couponIntroCN','$couponIntroTHAI','$pic','$amount','$type','$addtime')";
			$db->query($sql);
			//获取优惠券id
			$cid = $db->insert_id(); 
			//纸质再处理
			if ($type==2) {
				$data = array();
				$data['cid'] = $cid;
				$data['sid'] = $sid;
				$data['status'] = 0;
				for($i=0;$i<$amount;$i++){
					$data['code'] =$sid.'-'.substr(str_shuffle("ABCDEFGHJKMNPQRSTUVWXYZ123456789"),0,4);
					$db->insert('f_coupon_paper',$data);
				}
				
			}
			//编辑

		}else if($action == "update"){

			$sql = "update f_coupon set price = $price,couponNameCN = '$couponNameCN',couponNameTHAI = '$couponNameTHAI',couponIntroCN = '$couponIntroCN',couponIntroTHAI = '$couponIntroTHAI',pic = '$pic'".

			" where id = $id";

			$db->query($sql);

		}
		if ($type==1) {
			echo "<script>location.href='couponList.php'</script>";
		}else {
			echo "<script>location.href='couponListPaper.php'</script>";
		}
		

	}

?>