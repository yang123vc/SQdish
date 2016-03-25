<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	$id = $_GET['id']+0; 
	$db = new DB();
	$sql = "delete from f_email where id='$id'";
	if($db->query($sql)){
		echo "<script>alert('删除成功')</script>";
		echo "<script>location.href='emailList.php'</script>";
	}



?>