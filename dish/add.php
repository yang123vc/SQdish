<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	session_start();

	$sid = $_REQUEST['sid'];
	$did = $_REQUEST['did'];
	$dtid = $_REQUEST['dtid'];
	$nameCh = $_REQUEST['dishNameCN'];
	$nameThai = $_REQUEST['dishNameTHAI'];
	$opCn = $_REQUEST['opCn'];
	$opThai = $_REQUEST['opThai'];
	$op = $_REQUEST['op'];
	$num = $_REQUEST['num'];
	$price = $_REQUEST['price'];
	$opName="";
	$opNameThai="";
	$opId="";
	
	$cart = "";
	
	foreach($opCn as $k => $v){
		$opName .= $v." "; 
	}
	
	foreach($opThai as $k => $v){
		$opNameThai .= $v." "; 
	}

	foreach($op as $k => $v){
		$opId .= $v; 
	}
	$opId .= "_".$did;
	
	if(isset($_SESSION['cart']))
		$cart = $_SESSION['cart'];
	else
		$_SESSION['cart'] = $cart;
	
	if(isset($cart[$dtid][$opId]['num']))
		$cart[$dtid][$opId]['num'] += $num;
	else
		$cart[$dtid][$opId]['num'] = $num;
	
	$cart[$dtid][$opId]['nameCh'] = $nameCh;
	$cart[$dtid][$opId]['nameThai'] = $nameThai;
	$cart[$dtid][$opId]['price'] = $price;
	$cart[$dtid][$opId]['opName'] = $opName;
	$cart[$dtid][$opId]['opNameThai'] = $opNameThai;
	

	$_SESSION['cart'] = $cart;
	
	echo "<script>location.href='item.php?id=$did&sid=$sid&s=1'</script>";
?>