<?php
	header("content-type:text/html; charset=utf-8");
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	
	$content = $_REQUEST['content'];
	$type = $_REQUEST['type'];
	$option = $_REQUEST['option'];
	$pic = $_REQUEST['pic'];
	
	if(!$content)
		return "";
	
	$db = new DB();
	
	$result = "[";
	
	if($type == 1){
		$col = "cn";
	}else if($type == 2){
		$col = "thai";
	}
	
	$ids = explode(",",$option);
	
	$sql = "select * from f_dictionaries where $col like '%$content%'";
	$list = $db->get_all($sql);
	
	foreach($list as $lk => $lv){
		if($type == 1)
			$result .= "{item:'".$lv['cn']."(".$lv['thai'].")',show:";
		else if($type == 2)
			$result .= "{item:'".$lv['thai']."(".$lv['cn'].")',show:";
	
		$result .= "[";
		
		if($ids[0] != -1){
			$result .= "{value:'".$lv['cn']."',id:'".$ids[0]."'}," ;
		}
		if($ids[1] != -1){
			$result .= "{value:'".$lv['thai']."',id:'".$ids[1]."'}," ;
		}
		
		//去除末尾,
		$result = substr($result, 0, -1);
		
		if($pic == 1)
			$result .= "],pic:'".$lv['pic']."'},";
		else if($pic == -1)
			$result .= "]},";
	}
	
	//去除末尾,
	$result = substr($result, 0, -1);
	
	if(strlen($result) >0)
		$result .= "]";
	
	echo $result;
?>