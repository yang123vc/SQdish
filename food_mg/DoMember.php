<?php

	include_once dirname(__FILE__).'/../data/db/DB.php';

	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$db = new DB();
	$act = $_POST['action'];
	//会员编辑
	if ($act=='update') {
		$id = $_POST['id']+0;
		$date = array();
		$data['username'] = trim($data['username']);
		$data['thumbail'] = trim($data['thumbail']);
		$data['password'] = trim($data['password']);
		$data['nickname'] = trim($data['nickname']);
		$data['phone'] = trim($data['phone']);
		$data['email'] = trim($data['email']);
		$data['opt'] = trim($data['opt']);

		$rs = $db->update($data,"id='$id'");	
		if ($rs) {
			echo "<script>alert('会员信息编辑成功')</script>";	
			echo "<script>location.href='MemberList.php'</script>";
		}else{
			echo "<script>alert('会员信息编辑失败')</script>";	
			echo "<script>location.href='MemberUpdate.php?id=$id'</script>";
		}
	}






?>