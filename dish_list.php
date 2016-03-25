<?php
	ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	include_once dirname(__FILE__).'/inc/page.class.php';

	
	$db = new DB();
	//获取菜品分类
	$sql = "select distinct id,typeNameCN from f_dish_type order by rand() limit 30";
	$typeList = $db->get_all($sql);
	//分页参数
	$pageSize = 60;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	//根据菜品类型获取菜品
	$dtid = isset($_GET['dtid'])?$_GET['dtid']:'';
	if ($dtid=='') {
		//类型为空，获取所有菜品
		$sql = "select id,sid,dtid,pic,dishNameCN,price from f_dish order by id desc limit $location,$pageSize";
		$dishList = $db->get_all($sql);# code...
		$sql = "select count(*) from f_dish";
		$rs=$db->get_a($sql);
		//分页参数
		$count = intval($rs);
		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
	}else{
		$sql = "select id,sid,dtid,pic,dishNameCN,price from f_dish where dtid='$dtid' order by id desc limit $location,$pageSize";
		$dishList = $db->get_all($sql);# code...
		$sql = "select count(*) from f_dish where dtid='$dtid'";
		$rs = $db->get_a($sql);
		//分页参数
		$count = intval($rs);
		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
	}



							


	include_once dirname(__FILE__)."/templates/dish_list.html";
?>