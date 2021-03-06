<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	// print_r();
	$db = new DB();	//搜索参数	if(isset($_REQUEST['key']))
	$keywords = trim($_GET['key']);		
	if($keywords){
		$where = "where categoryNameZ like '%$keywords%' or categoryNameT like '%$keywords%' or categoryNameE like '%$keywords%'  ";
	}
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	
	$sql = "select * from f_article_category $where order by categoryRank asc limit $location,$pageSize";
	$List = $db->get_all($sql);
	
	$sql = "select count(*) as count from f_article_category $where";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	require("page.class.php");
	$pages = new page($pageSize,$count); 
	$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
	
	include_once dirname(__FILE__)."/templates/articleCategoryList.html";
?>