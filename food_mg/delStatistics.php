<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();
	
	$id = $_REQUEST['id'];
	
	$db->query("delete from f_statistics where id =$id");
	
	echo "<script>location.href='DishResultsList.php'</script>";
?>