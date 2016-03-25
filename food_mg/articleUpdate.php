<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	
	$db = new DB();
	
	$sql = "select * from f_user where role = 3";
	//$sql = "select * from f_article where role = 3";
	
	$referees  = $db->get_all($sql);
	
	$id = $_REQUEST['id'];
	$pic = "http://amui.qiniudn.com/bw-2014-06-19.jpg?imageView/1/w/1000/h/1000/q/80";
	$sql = "select * from f_seller where id = $id";
	$seller = $db->get_one($sql);
	
	$action = "update";
	
	if($seller){
		$sellerName = $seller['sellerName'];
		$coordinates = $seller['coordinates'];
		$address = $seller['address'];
		$contacts = $seller['contacts'];
		$tel = $seller['tel'];
		$intro = $seller['intro'];
		$pic = $seller['pic'];
		$path = $seller['path'];
		$referee = $seller['referee'];
		$country = $seller['country'];
	}
	
	include_once dirname(__FILE__)."/templates/ArticleAdd.html";
?>