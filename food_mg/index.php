<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	//根据不同权限显示不同功能菜单
	if($_SESSION[USERSESSION]['role']==3){
		include_once dirname(__FILE__).'/../data/db/DB.php';
		//获取推荐人信息
		$id = $_SESSION[USERSESSION]['id'];
		$sql = "select * from f_user where id='$id'";
		$db = new DB();
		$list = $db->get_all($sql);
		//获取推荐数
		$sql = "select count(*) from f_seller where referee='$id'";
		$sum = $db->get_a($sql);
		//正在审核数
		$sql = "select count(*) from f_seller where referee='$id' and is_on=0";
		$checking = $db->get_a($sql);
		//审核成功数
		$sql = "select count(*) from f_seller where referee='$id'  and is_on=1";
		$success = $db->get_a($sql);
		//print_r($list);exit;
		include_once dirname(__FILE__)."/templates/IndexReferee.html";
	}else{
		include_once dirname(__FILE__)."/templates/Index.html";
	}
	
?>