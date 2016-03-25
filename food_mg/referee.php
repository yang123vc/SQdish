<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	
	$db = new DB();	//搜索参数	
	if(isset($_REQUEST['key'])){
		$keywords = $_REQUEST['key'];
				if($keywords)	{
			$where = "where sellerName like '%$keywords%' or contacts like '%$keywords%' or tel like '%$keywords%'";		
		}		else	{
			$where = "";
		}	
	}
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	
	$sql = "select id,sellerName,tel,contacts,QRCode,dishPic,path from f_seller order by id desc limit $location,$pageSize";
	$sellerList = $db->get_all($sql);
	
	//餐厅管理员 组装
	for($i=0;$i<count($sellerList);$i++){
		$sid = $sellerList[$i]["id"];
		
		$sql = "select * from f_user where sid = $sid";
		$user = $db->get_one($sql);
		
		$sellerList[$i]['user'] = $user;
	}
	/*$sql = "select f_seller.id,sellerName,tel,contacts,QRCode,dishPic,path,f_user.username from f_seller left join f_user on f_seller.id=f_user.sid order by id desc limit $location,$pageSize";
	
	$sellerList = $db->get_all($sql);*/

	
	$sql = "select count(*) as count from f_seller ";
	$temp = $db->get_one($sql);
	$count = $temp['count'];
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	include_once dirname(__FILE__)."/templates/referee.html";``
?>