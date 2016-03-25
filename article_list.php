<?php
	//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	include_once dirname(__FILE__).'/inc/page.class.php';
	$db = new DB();
	$tid=intval($_GET['tid']);//栏目识别id
	//if(empty($tid)){
	//	echo "tid err!!";
	//	echo "<script>location.href='/'</script>";
	//	exit;
	//}
	
	//读取列表
	$pageSize = 9;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	if($tid){$where="where articleCategory=$tid ";}
	$sql = "select * from f_article $where order by id desc limit $location,$pageSize";
	$List = $db->get_all($sql);
	
	$sql = "select count(*) as count from f_article $where";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
		
	$pages = new page($pageSize,$count,$isw); 
	$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
							
							
							
	//读取所有栏目	
	$sql = "select * from f_article_category order by categoryRank asc limit 10";
	$CList = $db->get_all($sql);
	foreach($CList as $k=>$v){
		$newkey=$v['id'];
		$t[$newkey]=$v;
	}
	$CList=$t;
	//print_r($CList);
	
	//读取当前栏目信息
	if($tid){
			$sql = "select * from f_article_category where id = $tid ";		
		$cr  = $db->get_one($sql);//栏目信息
	}
	
	
	//读取当前栏目推荐信息
	if(empty($tid)){
		$where="where articleIst=1";
	}else{
		$where="where articleCategory=$tid  and articleIst=1";
	}
//echo $where;	
	$sqls = "select * from f_article $where order by id desc limit 10";
	$talists  = $db->get_all($sqls);//栏目推荐信息	
	//print_r($talists);
	
	
	//
	$agent = $_SERVER['HTTP_USER_AGENT'];  

	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {
		include_once dirname(__FILE__)."/templates/wap/article_list.html";
	}else {	

		include_once dirname(__FILE__)."/templates/article_list.html";
	};
	
?>