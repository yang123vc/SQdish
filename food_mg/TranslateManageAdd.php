<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();
	
	if(isset($_REQUEST['id']))
		$id = $_REQUEST['id'];
	else 
		exit;

	$sql = "select * from f_dictionaries where id = $id";
	$translate  = $db->get_one($sql);
	$action = "update";
	
	$id = $translate['id'];
	$cn = $translate['cn'];
	$thai = $translate['thai'];
	$pic = $translate['pic'];

	include_once dirname(__FILE__)."/templates/TranslateManageAdd.html";

?>