<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();
	
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
		
		$db->query("update f_dictionaries set pic = '' where id = $id");
		
		echo 1;
		return;
	}
	
	echo 2;
		
?>