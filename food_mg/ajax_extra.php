<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	
	$db = new DB();
	$sid = $_SESSION[SID];
	$status = $_POST['status']+0;
	$id = $_POST['id']+0;
	if ($status==1) {
		$sql ="update f_dish_extra set is_on=1 where ser_id='$id' and sid=0";
		if($db->query($sql)){
			echo 'success';
		}
	} 
	if($status==0) {
		$sql ="update f_dish_extra set is_on=0 where ser_id='$id' and sid=0";
		if($db->query($sql)){
			echo 'failure';
		}
	}
	
	


?>