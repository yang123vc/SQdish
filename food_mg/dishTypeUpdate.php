<?php
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	
	$db = new DB();
	
	$id = $_REQUEST['id'];
	$sql = "select * from f_dish_type where id = $id";
	$dishType = $db->get_one($sql);
	
	$action = "update";
	
	if($dishType){
		$typeNameCN = $dishType['typeNameCN'];
		$typeNameTHAI = $dishType['typeNameTHAI'];
	}
	
	include_once dirname(__FILE__)."/templates/DishTypeAdd.html";
?>