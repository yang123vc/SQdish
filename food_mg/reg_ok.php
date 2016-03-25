<?php
	header("content-type:text/html;charset=utf-8");
	include_once dirname(__FILE__).'/../data/db/DB.php';
	//接收数据
	$db = new DB();
	$data = array();
	$data['role'] = $_POST['role']+0;
	$data['username'] = trim($_POST['username']);
	$data['password'] = trim($_POST['pwd']);
	$data['email'] = trim($_POST['email']);
	$data['phone'] = $_POST['phone']+0;
	$data['city'] = trim($_POST['city']);
	$data['reg_time'] = time();

	//数据验证
	if (empty($data['username'])) {
		echo "<script>alert('用户名不能为空')</script>";
		echo "<script>location.href='reg.php'</script>";
		exit;
	}else{
		$sql = "select count(*) from f_user where username='$data[username]'";
		$rs = $db->get_a($sql);
		if ($rs!=0) {
		echo "<script>alert('用户名已存在')</script>";
		echo "<script>location.href='reg.php'</script>";
		exit;
		}
	}
	if ($data['role']!=3) {
		echo "<script>alert('权限有误')</script>";
		echo "<script>location.href='reg.php'</script>";
		exit;
	}
	if (empty($data['password'])) {
		echo "<script>alert('密码不能为空')</script>";
		echo "<script>location.href='reg.php'</script>";
		exit;
	}
	if ($data['email']=='') {
		echo "<script>alert('邮箱不能为空')</script>";
		echo "<script>location.href='reg.php'</script>";
		exit;
	}else{
		$sql = "select count(*) from f_user where email='$data[email]'";
		$rs = $db->get_a($sql);
		if($rs!=0){
			echo "<script>alert('此邮箱已存在')</script>";
			echo "<script>location.href='reg.php'</script>";
			exit;
		}
	}
	if (empty($data['phone'])) {
		echo "<script>alert('电话不能为空')</script>";
		echo "<script>location.href='reg.php'</script>";
		exit;
	}
	if (empty($data['city'] )) {
		echo "<script>alert('城市不能为空')</script>";
		echo "<script>location.href='reg.php'</script>";
		exit;
	}

	//调用model入库
	 if($db->insert('f_user',$data)){
	 	echo "<script>alert('注册成功')</script>";
		echo "<script>location.href='login.php'</script>";
	 }else{
	 	echo "<script>alert('注册失败')</script>";
		echo "<script>location.href='reg.php'</script>";
	 }

?>