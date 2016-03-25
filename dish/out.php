<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	session_start();

	$id = $_REQUEST['id'];
	
	if(isset($_SESSION["cart"])){
		unset($_SESSION["cart"]);
	}
	if ($id) {
		echo "<script>location.href='index.php?id=$id'</script>";
	}else{
		echo "<script>location.href='../user_index.php'</script>";
	}
	
?>