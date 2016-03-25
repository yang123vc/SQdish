<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$db = new DB();	
	
if($_POST['action']=='add'){
	$data['categoryNameZ']=htmlspecialchars($_POST['categoryNameZ']);
	$data['categoryNameT']=htmlspecialchars($_POST['categoryNameT']);
	$data['categoryNameE']=htmlspecialchars($_POST['categoryNameE']);
	$data['categorykeyword']=htmlspecialchars($_POST['categorykeyword']);
	$data['categorydes']=htmlspecialchars($_POST['categorydes']);
	$data['categoryRank']=intval($_POST['categoryRank']);
	$r  = $db->insert('f_article_category',$data);	
	if($r){
		echo "ok ";
		echo "<script>location.href='ArticleCategoryList.php'</script>";
	}else{
		echo "err";
		echo "<script>location.href='ArticleCategoryList.php'</script>";
	}
	
	exit;
}
	include_once dirname(__FILE__)."/templates/ArticleCategoryAdd.html";
?>