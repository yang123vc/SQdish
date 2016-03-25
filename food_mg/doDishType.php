<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	
	//dish 依赖
	$db = new DB();
	$sid = $_REQUEST[SID];
	
	//基础参数
	$action = $_REQUEST['action'];
	$id = $_REQUEST['id'];
	
	if($action == "del"){
		$sql = "delete from f_dish_type where id = $id";
		$db->query($sql);
		
		echo "<script>location.href='dishType.php'</script>";
		exit;
	}

	//参数
	$typeNameCN = $_POST['typeNameCN'];
	$typeNameCN = replace($typeNameCN, "'", "\'");
	$typeNameTHAI = $_POST['typeNameTHAI'];
	$typeNameTHAI = replace($typeNameTHAI, "'", "\'");
	
	
	if($action == "add"){
		$sql = "insert into f_dish_type(sid,typeNameCN,typeNameTHAI) values ($sid,'$typeNameCN','$typeNameTHAI')";
		$db->query($sql);
	}else if($action == "update"){
		$sql = "update f_dish_type set typeNameCN='$typeNameCN',typeNameTHAI='$typeNameTHAI' where id =$id";
		$db->query($sql);
	}
	
	echo "<script>location.href='dishType.php'</script>";
?>