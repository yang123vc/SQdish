<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../util/param.php';
	
	$db = new DB();
	
	$exchangeRate = getValue("exchangeRate");
	$statisticalCode = getValue("statisticalCode");
	
	include_once dirname(__FILE__)."/templates/System.html";
?>