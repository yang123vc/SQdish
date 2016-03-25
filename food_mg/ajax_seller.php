<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$db = new DB();

	$sellerName = trim($_GET['sellerName']);
	$sellerName = replace($sellerName, "'", "\'");
	if(isset($sellerName)){
	            $sql = "select count(*) from f_seller where sellerName = '$sellerName'";
           		echo $db->get_a($sql);
	}

	
	


?>