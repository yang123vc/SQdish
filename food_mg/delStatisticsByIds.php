<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	
	$db = new DB();
	
	//条件判断数据（基础依赖数据）
	$ids = $_REQUEST['ids'];

	//删除
	$sql = "delete from f_statistics where id in($ids)";
	$db->query($sql);

	echo "<script>location.href='DishResultsList.php'</script>";
?>