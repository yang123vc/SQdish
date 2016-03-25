<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	
	//dish 依赖
	$db = new DB();

	$id = $_REQUEST['id'];
	$action = $_REQUEST['action'];
	
	if($action == "del"){
		$sql = "delete from f_dictionaries where id = $id";
		$db->query($sql);
		echo "<script>location.href='TranslateManage.php'</script>";
		exit;
	}else if($action == "update"){		$cn = $_REQUEST['cn'];		$thai = $_REQUEST['thai'];		$pic = $_REQUEST['pic'];				$sql = "update f_dictionaries set cn = '$cn',thai = '$thai',pic = '$pic' where id = $id";		$db->query($sql);		echo "<script>location.href='TranslateManage.php'</script>";		exit;	}
	echo "<script>location.href='TranslateManage.php'</script>";
?>