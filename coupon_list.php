<?php
	//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	include_once dirname(__FILE__).'/inc/page.class.php';
	$db = new DB();

	//读取列表
	$pageSize = 60;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;

	//
	$sql = "select count(1) as num from f_coupon";
	$count_arr = $db->get_one($sql);
	$count = $count_arr['num'];
	//数组分页
	$sql = "select * from f_coupon where status = 0 order by id desc limit $location,$pageSize";
	$couponList = $db->get_all($sql);
		
	$pages = new page($pageSize,$count,$isw); 
	$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
							
	
	include_once dirname(__FILE__)."/templates/coupon_list.html";
?>