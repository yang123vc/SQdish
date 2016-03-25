<?php
//ini_set("display_errors",1);
//error_report(E_ALL);
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$id = $_GET['id']+0;
	$db = new DB();
	$sql = "select * from f_email where id='$id'";
	$list = $db->get_one($sql); 
	include_once dirname(__FILE__)."/templates/emailEdit.html";
?>