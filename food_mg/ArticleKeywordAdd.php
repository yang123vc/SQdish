<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
		
	//$sql = "select * from f_user where role = 3";		
	//$sql = "select * from f_Keyword where role = 3";		/*表结构	表名 f_Keyword	===================	关键词名称 KeywordName	关键词地址 KeywordUrl	关键词频次 KeywordNum	*/
	//$referees  = $db->get_all($sql);	
	//$action = "add";
if($_POST['action']=='add'){
	$db = new DB();
	$data['KeywordName']=htmlspecialchars($_POST['KeywordName']);
	$data['KeywordUrl']=htmlspecialchars($_POST['KeywordUrl']);
	$data['KeywordNum']=intval($_POST['KeywordNum']);
	$r  = $db->insert('f_article_keywords',$data);	
	if($r){
		echo "ok ";
		echo "<script>location.href='articleKeywordList.php'</script>";
	}else{
		echo "err";
		echo "<script>location.href='articleKeywordList.php'</script>";
	}
	
	
	
	exit;
}


	
	include_once dirname(__FILE__)."/templates/ArticleKeywordAdd.html";
?>