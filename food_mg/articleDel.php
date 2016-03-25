<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
		


	$db = new DB();
	$id=trim($_GET['id']);
 $id=trim($id,',');
	$ids=explode(',',$id);
	foreach($ids as $k=>$v){
		$idss[$k]=intval($v);
	}
 $id=implode(',',$idss);
	$condition=" id in ($id) ";
	$db->delete('f_article',$condition);
	echo "<script>location.href='articleList.php'</script>";
	

?>