<?php
	include_once dirname(__FILE__).'/data/db/DB.php';
	
	$db = new DB();

	$mid = $_POST['mid'];
	$cid = $_POST['cid'];
	$act = $_POST['act'];
	$sql = "select * from f_coupon where id = '$cid'";
	$coupon_now = $db->get_one($sql);
	$sid = $coupon_now['sid'];
	$price = $coupon_now['price'];
	/*echo $sid;
	echo $price;*/
	if ($act=='reduce') {
	     	$sql = "SELECT id FROM f_coupon_tmp WHERE mid ='$mid' AND cid ='$cid' AND sid ='$sid' ORDER BY id DESC LIMIT 1";
		$d_id = $db->get_a($sql);
		$sql = "delete from f_coupon_tmp where id='$d_id'";
		$db->query($sql);
	}else{
		$sql = "insert into f_coupon_tmp(mid,cid,sid,price) values('$mid','$cid','$sid','$price')";
		$if_success = $db->query($sql);
	}

	/*if ($if_success) {
		echo 'success';
	}
	else{
		echo 'fail';
	}*/
	$sql = "select id,couponNameCN,price from f_coupon where sid='$sid' and status = 0";
	$list = $db->get_all($sql);
	foreach ($list as $key => $value) {
		$value['num'] = $db->get_a("select count(*) from f_coupon_tmp where mid='$mid' and sid='$sid' and cid ='$value[id]' and status =0");
		$value['count'] = $value['num']*$value['price'];
		if ($value['num']!=0) {
			$couponList[] = $value;
		}
	}
	echo json_encode($couponList);
?>