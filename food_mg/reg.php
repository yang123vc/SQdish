<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	session_start();
	
	if(isset($_SESSION['language'])){
		if($_SESSION['language'] == "en")
			include_once dirname(__FILE__).'/../inc/en.php';
		
		if($_SESSION['language'] == "thai")
			include_once dirname(__FILE__).'/../inc/thai.php';
		
		if($_SESSION['language'] == "cn")
			include_once dirname(__FILE__).'/../inc/cn.php';
	}else{
		$_SESSION['language'] = "en";
	}
	
	$db = new DB();

	$error = "";
	
	include_once dirname(__FILE__)."/templates/reg.html";
?>