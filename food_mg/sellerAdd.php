<?php

	include_once dirname(__FILE__).'/../data/db/DB.php';

	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	

	$db = new DB();

	$sql = "select * from f_user where role = 3";

	$referees  = $db->get_all($sql);
	//获取图标
	$sql = "select ser_id,ser_name,ser_icon from f_dish_extra where is_on=1 and sid=0";
	$extra = $db->get_all($sql);
	$extraList = array();
	foreach ($extra as $v) {
		$v['status']=0;
		$extraList[] = $v;
	}
	//获取餐厅分类
	$sql = "select rt_id,rt_name from f_dish_rtype where is_on=1 and sid=0";
	$rtype =  $db->get_all($sql);
	$rtypeList = array();
	foreach ($rtype as $v) {
		$v['status']=0;
		$rtypeList[] = $v;
	}
	$action = "add";

	if($_SESSION[USERSESSION]['role']==3){
		include_once dirname(__FILE__)."/templates/refereeSellerAdd.html";
	}else{
		include_once dirname(__FILE__)."/templates/SellerAdd.html";
	}	


?>