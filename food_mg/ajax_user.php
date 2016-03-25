<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$db = new DB();

	$username = trim($_GET['username']);
	$username = replace($username, "'", "\'");
	if(isset($username)){
	            $sql = "select count(*) from f_user where username = '$username'";
           		echo $db->get_a($sql);
	}

	
	


?>