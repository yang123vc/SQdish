<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	session_start();

	$dtid = $_REQUEST['dtid'];
	$did = $_REQUEST['did'];
	$nameCh = $_REQUEST['nameCh'];
	$nameThai = $_REQUEST['nameThai'];
	$price = $_REQUEST['price'];
	$opName = $_REQUEST['opName'];
	$opNameThai = $_REQUEST['opNameThai'];
	$type = $_REQUEST['type'];
	$opId = $_REQUEST['opId'];
	
	//购物车
	$cart = "";
	
	$opId = $opId."_".$did;

	if(isset($_SESSION['cart']))
		$cart = $_SESSION['cart'];
	else
		$_SESSION['cart'] = $cart;
	
	$db = new DB();
	
	$cart[$dtid][$opId]['nameCh'] = $nameCh;
	$cart[$dtid][$opId]['nameThai'] = $nameThai;
	$cart[$dtid][$opId]['price'] = $price;
	$cart[$dtid][$opId]['opName'] = $opName;
	$cart[$dtid][$opId]['opNameThai'] = $opNameThai;
	
	if($type == "add"){
		if(isset($cart[$dtid][$opId]['num']))
			$cart[$dtid][$opId]['num'] += 1;
		else
			$cart[$dtid][$opId]['num'] = 1;
	}else if($type == "minus"){
		if(isset($cart[$dtid][$opId]['num'])){
			if($cart[$dtid][$opId]['num'] > 0)
				$cart[$dtid][$opId]['num'] -= 1;
		}else{
			$cart[$dtid][$opId]['num'] = 0;
		}
	}
	
	$_SESSION['cart'] = $cart;
	
	$result = getTotalResult($cart);
	$result['num'] = $cart[$dtid][$opId]['num'];
	
	echo json_encode($result);
?>