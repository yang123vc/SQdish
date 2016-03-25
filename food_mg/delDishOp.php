<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	
	$db = new DB();
	
	//条件判断数据（基础依赖数据）
	$id = $_REQUEST['id'];

	//删除
	$sql = "delete from f_dish_options where id = $id";
	$db->query($sql);
?>