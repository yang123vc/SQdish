<?php

	include_once dirname(__FILE__).'/../data/db/DB.php';

	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$db = new DB();
	
	$id = $_GET['id']+0;
	$sql = "select * from f_member where id='$id'";
	$m = $db->get_one($sql);

	include_once dirname(__FILE__)."/templates/MemberUpdate.html";
		


?>