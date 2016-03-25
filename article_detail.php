<?php
//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';

	if(!session_id())
	session_start();

	$db = new DB();
	$tid=intval($_GET['tid']);//文章识别id
	if(empty($tid)){
		echo "tid err!!";
		echo "<script>location.href='/'</script>";
		exit;
	}
	//栏目
	$sql = "select * from f_article_category order by categoryRank asc limit 10";
	$CList = $db->get_all($sql);
	foreach($CList as $k=>$v){
		$newkey=$v['id'];
		$t[$newkey]=$v;
	}
	$CList=$t;
	//开始读取文章信息
	$sql = "select * from f_article where id = $tid ";		
	$r  = $db->get_one($sql);	
	if(empty($r)){
		echo "info err!!";
		echo "<script>location.href='/'</script>";
		exit;
	}
	$Categoryid=$r['articleCategory'];//栏目id
	//读取栏目信息
	$sql = "select * from f_article_category where id = $Categoryid ";		
	$cr  = $db->get_one($sql);//栏目信息	
	
	//读取该篇文章的相关推荐列表
	$sql = "select * from f_article_rcommend where articleId=$tid order by id desc";
	$Tlist=$db->get_all($sql);
//print_r($Tlist);


	//读取当前栏目推荐信息
	if(empty($Categoryid)){
		$where="where articleIst=1";
	}else{
		$where="where articleCategory=$Categoryid  and articleIst=1";
	}
//echo $where;	
	$sqls = "select * from f_article $where order by id desc limit 10";
	$talists  = $db->get_all($sqls);//栏目推荐信息

	$url = "http://".$_SERVER['HTTP_HOST'];
	$mid = 0;
	if(isset($_SESSION['user_info']))
	{
		$mid = $_SESSION['user_info']['id'];

	} else {
		$mid = 0;
	}

	$fid = $tid;
	$fav_article = $db->get_one("select * from f_favourite where status=0 and mid= ".intval($mid)." and fid=".$fid);
   // print_r($fav_article);exit;
	$fav_flag = 0;
	if(empty($fav_article))
	{
		$fav_flag = 0;
	} else {
		$fav_flag = 1;
	}

	//print_r($fav_flag);exit;

	$agent = $_SERVER['HTTP_USER_AGENT'];  

	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

		include_once dirname(__FILE__)."/templates/wap/article_detail.html";

	}else {	

		include_once dirname(__FILE__)."/templates/article_detail.html";

	};
?>