<?php
	include_once dirname(__FILE__).'/data/db/DB.php';
	$db = new DB();
	$type = $_GET['type'];
	$sid = $_GET['sid'];

	//获取分类的id
	$sql = "select id from f_dish_type where sid='$sid' and typeNameCN='$type'";
	$type_id = $db->get_a($sql);

	//根据分类id获取菜品
	$sql = "select id,dishNameCN,dishNameTHAI,dishIntroCN,pic,price,used from f_dish where dtid='$type_id' order by id desc limit 5";
	$dish_list = $db->get_all($sql);

	echo json_encode($dish_list);

?>