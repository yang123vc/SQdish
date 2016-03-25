<?php
	header("content-type:text/html;charset=utf-8");
	include_once dirname(__FILE__).'/../data/db/DB.php';
	//接收数据
		$db = new DB();
	$id = $_POST['id']+0;
	$data = array();
	$data['role'] = $_POST['role']+0;
	$data['username'] = trim($_POST['username']);
	$data['password'] = trim($_POST['pwd']);
	$data['email'] = trim($_POST['email']);
	$data['phone'] = $_POST['phone']+0;
	$data['city'] = $_POST['city'];
	$data['card'] = $_POST['card'];
	$data['firstName'] = trim($_POST['firstName']);
	$data['surname'] = trim($_POST['surname']);
	$data['reg_time'] = time();

	//数据验证
	if (empty($data['username'])) {
		echo "<script>alert('用户名不能为空')</script>";
		echo "<script>location.href='refereeEdit.php?id=$id'</script>";
		exit;
	}	
	if ($data['role']!=3) {
		echo "<script>alert('权限有误')</script>";
		echo "<script>location.href='refereeEdit.php?id=$id'</script>";
		exit;
	}
	if (empty($data['password'])) {
		echo "<script>alert('密码不能为空')</script>";
		echo "<script>location.href='refereeEdit.php?id=$id'</script>";
		exit;
	}
	if ($data['email']=='') {
		echo "<script>alert('邮箱不能为空')</script>";
		echo "<script>location.href='refereeEdit.php?id=$id'</script>";
		exit;
	}
	if (empty($data['phone'])) {
		echo "<script>alert('电话不能为空')</script>";
		echo "<script>location.href='refereeEdit.php?id=$id'</script>";
		exit;
	}
	if (empty($data['city'] )) {
		echo "<script>alert('城市不能为空')</script>";
		echo "<script>location.href='refereeEdit.php?id=$id'</script>";
		exit;
	}

	//更新数据库操作

	if($db->update('f_user',$data,"id='$id'")){
		echo "<script>alert('修改成功')</script>";
		echo "<script>location.href='index.php'</script>";
	}else{
		echo "<script>alert('修改失败')</script>";
		echo "<script>location.href='index.php'</script>";
	}



?>
