<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$sid = $_REQUEST['sid'];
	$to = $_REQUEST['to'];
	
	$_SESSION[SID] = $sid;
	
	if($to == "add"){
		echo "<script>location.href='dishAdd.php'</script>";
	}else if($to == "main"){
		echo "<script>location.href='dishList.php'</script>";
	}
?>