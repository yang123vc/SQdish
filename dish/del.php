<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	session_start();


	$dtid = $_REQUEST['dtid'];
	$opId = $_REQUEST['opId'];
	
	$cart = "";
	if(isset($_SESSION['cart']))
		$cart = $_SESSION['cart'];

	if(isset($cart[$dtid][$opId])){
		unset($cart[$dtid][$opId]);
		$_SESSION['cart'] = $cart;
	}
?>