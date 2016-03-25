<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	session_start();

	$db = new DB();
	
	$sid = $_REQUEST['id'];
	$seller = $db->get_one("select * from f_seller where id = $sid");
	$dish = $db->get_all("select * from f_dish where sid = $sid");
	$dishType = $db->get_all("select * from f_dish_type where sid = $sid");
	
	//购物车
	$cart = "";

	if(isset($_SESSION['cart']))
		$cart = $_SESSION['cart'];
	else
		$_SESSION['cart'] = $cart;
		
	$result = getTotalResult($cart);	

	include_once dirname(__FILE__)."/booking.html";
?>