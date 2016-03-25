<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();	
	$action=trim($_POST['action']);
	if($action=='add'){//处理新增一个
		//print_r($_POST);
		$data['title']=trim($_POST['title']);
		$data['picturePath']=trim($_POST['picturePath']);
		$data['articleUrl']=trim($_POST['articleUrl']);
		$data['description']=trim($_POST['description']);
		$data['articleId']=$aid=intval($_POST['aid']);
		$r  = $db->insert('f_article_rcommend',$data);	
		if($r){
			echo "ok ";
			echo "<script>location.href='articleRecommend.php?aid=$aid'</script>";
		}else{
			echo "err";
			echo "<script>location.href='articleRecommend.php?aid=$aid'</script>";
		}
		
		
		exit;
	}elseif($action=='edit'){//处理编辑一个
		$data['title']=trim($_POST['title']);
		$data['picturePath']=trim($_POST['picturePath']);
		$data['articleUrl']=trim($_POST['articleUrl']);
		$data['description']=trim($_POST['description']);
		$data['articleId']=$aid=intval($_POST['aid']);
		$id=intval($_POST['rid']);
		$r  = $db->update('f_article_rcommend',$data," id='$id' ");		
	if($r){
		echo "ok ";
		echo "<script>location.href='articleRecommend.php?aid=$aid'</script>";
	}else{
		echo "err";
		echo "<script>location.href='articleRecommend.php?aid=$aid'</script>";
	}
		exit;
	}
	
	//get提交开始
	$action=trim($_GET['action']);
	$aid=intval($_GET['aid']);
	if(empty($aid)){
		echo "aid err!";
		echo "<script>location.href='articleList.php'</script>";
		exit;
	}
	
	if($action=='del'){		//get提交删除
		$rid=intval($_GET['rid']);
		$condition=" id = $rid ";
		$db->delete('f_article_rcommend',$condition);
		echo "<script>location.href='articleRecommend.php?aid=$aid'</script>";	
	}
	
	
	
	

	
	if($action=='edit'){//编辑模式 读取信息
		$rid=intval($_GET['rid']);
		if(empty($aid)){
			echo "rid err!";
			echo "<script>location.href='articleList.php'</script>";
			exit;
		}
		$sql = "select * from f_article_rcommend where id = $rid ";		
		$rinfo  = $db->get_one($sql);			
	}
	
	//读取所有列表
	$sql = "select * from f_article_rcommend where articleId=$aid order by id desc";
	$List = $db->get_all($sql);
	
	include_once dirname(__FILE__)."/templates/ArticleRecommend.html";
?>