<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
		$db = new DB();

	$email = trim($_GET['email']);

	if(isset($email)){
	            $sql = "select count(*) from f_user where email = '$email'";
           		echo $db->get_a($sql);
	}

	
	


?>