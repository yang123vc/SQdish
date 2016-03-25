<?php
ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	$db = new DB();
	$tid=intval($_GET['tid']);//文章识别id
	if(empty($tid)){
		exit;
	}
	$ip=getIP();
	
	//开始读取文章信息
$sql = "select * from f_article_count where ip = '$ip' and id=$tid ";		
$r  = $db->get_one($sql);
	//print_r($r);
	if((time()-$r['time'])>12*3600){
		$sql = "update f_article set articleClick=articleClick+1 where id= $tid";
		$db->query($sql);
		$data['time']=time();
		if($r){			
		$r  = $db->update('f_article_count',$data," ip='$ip' and id=$tid ");
		}else{
			$data['ip']=$ip;
			$data['id']=$tid;
			$r  = $db->insert('f_article_count',$data);
		}
		
		exit;
		
	}else{
		exit;
	}
	
	
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
	
	
	include_once dirname(__FILE__)."/templates/article_detail.html";
?>