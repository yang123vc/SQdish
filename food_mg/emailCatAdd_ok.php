<?php
//ini_set("display_errors",1);
//error_report(E_ALL);
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$db = new DB();
	//接收数据
	//print_r($_POST);exit();
	$data = array();
	$data['cat_name'] = trim($_POST['cat_name']);
	//$data['parent_id'] = $_POST['parent_id']+0;
	//验证数据合法性
	if (empty($data['cat_name'])) {
		echo "<script>alert('Please fill in the correct information');</script>";
		echo "<script>location.href='emailCatAdd.php'</script>";
		exit;
	}else{
		$sql = "select count(*) from f_email_cat where cat_name='$data[cat_name]'";
		if ($db->get_a($sql)) {
			echo "<script>alert('Please fill in the correct information');</script>";
			echo "<script>location.href='emailCatAdd.php'</script>";
			exit;
		}
	}
	/*
	if ($data['parent_id']<0) {
		echo "<script>alert('Please fill in the correct information');</script>";
		echo "<script>location.href='emailCatAdd.php'</script>";
		exit;
	}
		*/
	$rs = $db->insert('f_email_cat',$data);
	if ($rs) {
		echo "<script>alert('添加成功')</script>";# code...
		echo "<script>location.href='emailCatAdd.php'</script>";
	}

	include_once dirname(__FILE__)."/templates/emailCatAdd.html";
?>