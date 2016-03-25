<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	$action = "add";
	$sid = $_SESSION[SID];
	
	include_once dirname(__FILE__)."/templates/DishTypeAdd.html";
?>