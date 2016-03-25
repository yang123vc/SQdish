<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
		
	$db = new DB();
if($_POST['action']=='edit'){	
	$data['KeywordName']=htmlspecialchars($_POST['KeywordName']);
	$data['KeywordUrl']=htmlspecialchars($_POST['KeywordUrl']);
	$data['KeywordNum']=intval($_POST['KeywordNum']);
	$id=intval($_POST['id']);
	if(empty($id)){
		echo "id err!";
		echo "<script>location.href='articleKeywordList.php'</script>";
		exit;
	}
	$r  = $db->update('f_article_keywords',$data," id='$id' ");	
	if($r){
		echo "ok ";
		echo "<script>location.href='articleKeywordList.php'</script>";
	}else{
		echo "err";
		echo "<script>location.href='articleKeywordList.php'</script>";
	}	
	exit;
}

		$id=intval($_GET['id']);
		$sql = "select * from f_article_keywords where id = $id ";		
		$r  = $db->get_one($sql);	
		if(empty($r)){
			echo "id err!";
			echo "<script>location.href='ArticleKeywordAdd.php'</script>";
			exit;
		}
	//	print_r($r);


	
	include_once dirname(__FILE__)."/templates/ArticleKeywordEdit.html";
?>