<?php
//ini_set("display_errors",1);
//error_report(E_ALL);
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../inc/articleFunction.php';
	$db = new DB();
	//print_r($_POST);
	
	if($_POST['action']=='add'){		
		if(empty($_POST['articleTitle'])){			
			echo " Title  empty ";
			echo "<script>location.href='articleAdd.php'</script>";exit;	
		}else{
			$data['articleTitle']=htmlspecialchars(trim($_POST['articleTitle']));//标题
		}
		$data['articleCategory']=intval($_POST['articleCategory']);
		$data['articleAuthor']=htmlspecialchars($_POST['articleAuthor']);
		$data['articleClick']=intval($_POST['articleClick']);
		$data['articleKeyword']=htmlspecialchars($_POST['articleKeyword']);	
		$data['articleDes']=trim(htmlspecialchars($_POST['articleDes']));
		$data['articleIst']=intval($_POST['articleIst']);		
		$data['articlePic']=htmlspecialchars($_POST['articlePic']);
		$data['articleThumb']=htmlspecialchars($_POST['articleThumb']);
		$data['articleIsw']=intval($_POST['articleIsw']);
		$data['articleIsm']=intval($_POST['articleIsm']);
		$data['ip']=getIP();
		$data['addtime']=time();
		$data['trueAuthor']=$_SESSION[USERSESSION]['username'];		
		$data['articleBody']=$_POST['articleBody'];
		
		
		
		
		if($data['articleIsm']){
			//锚文本处理
		$keyword=$db->get_all("select * from f_article_keywords order by KeywordNum desc");	
		$data['articleBody']=mwb($data['articleBody'],$keyword);			
		}	
		if($data['articleIsw']){
			$data['articleBody']=wyc($data['articleBody']);
			//伪原创处理
		}		
		if(empty($data['articleDes'])){
$data['articleDes']=cn_substr_utf8(SpHtml2Text($data['articleBody']),80);//自动描述处理
		}
		
		if(!get_magic_quotes_gpc()){
			$data=add_slashes($data);
		}
		
		$r  = $db->insert('f_article',$data);	
		if($r){
		//	echo "ok ";
		//	echo "<script>alert('增加成功');</script>";
			echo "<script>location.href='articleList.php'</script>";
		}else{
		//	echo "err";
			echo "<script>location.href='articleList.php'</script>";
		}
	
		
		
		
		
		exit;
	}
	$sql = "select * from f_article_category order by id desc ";
	$category = $db->get_all($sql);	
	include_once dirname(__FILE__)."/templates/ArticleAdd.html";
?>