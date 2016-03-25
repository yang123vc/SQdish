<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	$db = new DB();
	$sql = "select * from f_dish_options_config";
	$options  = $db->get_all($sql);
	include_once dirname(__FILE__)."/templates/OptionManage.html";
?>