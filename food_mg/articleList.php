<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../inc/articleFunction.php';
	$where=" where 1=1 ";
	$db = new DB();	//搜索参数	if(isset($_REQUEST['key']))
	$keywords = trim($_GET['key']);		
	if($keywords){
		$where .= "and articleTitle like '%$keywords%' ";
	}
	$tid=intval($_GET['tid']);
	if($tid){
		$where .= "and articleCategory =$tid ";
	}
	
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	
	$sql = "select * from f_article $where order by id desc limit $location,$pageSize";
	$List = $db->get_all($sql);
	
	$sql = "select count(*) as count from f_article $where";
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
							
							
		//读取所有栏目	
	$sql = "select * from f_article_category order by categoryRank asc";
	$CList = $db->get_all($sql);
	foreach($CList as $k=>$v){
		$newkey=$v['id'];
		$t[$newkey]=$v;
	}
	$CList=$t;
	
	include_once dirname(__FILE__)."/templates/ArticleList.html";
?>