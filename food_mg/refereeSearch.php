<?php

	include_once dirname(__FILE__).'/../data/db/DB.php';

	include_once dirname(__FILE__).'/../inc/isLogin.config.php';



	$key = $_REQUEST['key'];
	$key = replace($key, "'", "\'");
	$id = $_POST['id']+0;

	

	$db = new DB();
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;
	
	$sql = "select id,sellerName,tel,contacts,QRCode,dishPic,path,is_on from f_seller where referee='$id' and sellerName like '%$key%' or contacts like '%$key%' or tel like '%$key%'";
	$sellerList = $db->get_all($sql);
	
	//餐厅管理员 组装
	for($i=0;$i<count($sellerList);$i++){
		$sid = $sellerList[$i]["id"];
		
		$sql = "select username from f_user where sid = $sid";
		$user = $db->get_one($sql);
		
		$sellerList[$i]['user'] = $user;
	}

	$sql = "select count(*) as count from f_seller where referee='$id' and sellerName like '%$key%' or contacts like '%$key%' or tel like '%$key%'";

	$temp = $db->get_one($sql);

	$count = $temp['count'];
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;

	

	include_once dirname(__FILE__)."/templates/re_SellerList.html";

?>