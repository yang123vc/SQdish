<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../util/param.php';
	
	$db = new DB();
	
	$statisticalCode = $_REQUEST['statisticalCode'];
	$exchangeRate = $_REQUEST['exchangeRate'];
	
	setValue("statisticalCode",$statisticalCode);
	setValue("exchangeRate",$exchangeRate);

	echo "<script>location.href='System.php'</script>";
	
?>