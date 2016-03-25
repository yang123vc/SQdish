<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$db = new DB();	
	
if($_POST['action']=='edit'){
	$data['categoryNameZ']=htmlspecialchars($_POST['categoryNameZ']);
	$data['categoryNameT']=htmlspecialchars($_POST['categoryNameT']);
	$data['categoryNameE']=htmlspecialchars($_POST['categoryNameE']);
	$data['categorykeyword']=htmlspecialchars($_POST['categorykeyword']);
	$data['categorydes']=htmlspecialchars($_POST['categorydes']);
	$data['categoryRank']=intval($_POST['categoryRank']);
	$id=intval($_POST['id']);
	if(empty($id)){
		echo "id err!";
		echo "<script>location.href='ArticleCategoryList.php'</script>";
		exit;
	}
	
	
	//$r  = $db->update('',$data);
	$r  = $db->update('f_article_category',$data," id='$id' ");		
	if($r){
		echo "ok ";
		echo "<script>location.href='ArticleCategoryList.php'</script>";
	}else{
		echo "err";
		echo "<script>location.href='ArticleCategoryList.php'</script>";
	}
	
	exit;
}

$id=intval($_GET['id']);
$sql = "select * from f_article_category where id = $id ";		
$r  = $db->get_one($sql);	
if(empty($r)){
	echo "id err!!";
	echo "<script>location.href='ArticleCategoryList.php'</script>";
	exit;
}




	include_once dirname(__FILE__)."/templates/ArticleCategoryEdit.html";
?>