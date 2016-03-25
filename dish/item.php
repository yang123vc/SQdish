<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	include_once dirname(__FILE__).'/../util/param.php';
	session_start();
	
	$db = new DB();
	
	$id = $_REQUEST['id'];
	$sid = $_REQUEST['sid'];
	$s = isset($_REQUEST['s']) ? $_REQUEST['s']:0;
	$dish = $db->get_one("select * from f_dish where id = $id");
	
	//购物车
	$cart = "";

	if(isset($_SESSION['cart']))
		$cart = $_SESSION['cart'];
	else
		$_SESSION['cart'] = $cart;
	
	$statisticalCode = getValue('statisticalCode');
	
	$result = getTotalResult($cart);	
	
	//属性
	$sql = "select * from f_dish_options where did = $id";
	$options = $db->get_all($sql);
	
	include_once dirname(__FILE__)."/item.html";
?>