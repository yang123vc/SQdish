<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../util/phpExcel/PHPExcel.php';
	include_once dirname(__FILE__).'/../util/phpExcel/excelUtil.php';
	include_once dirname(__FILE__).'/../util/phpExcel/PHPExcel/Writer/Excel2007.php';
	
	$db = new DB();
	
	// 创建一个处理对象实例 
	$objExcel = new PHPExcel(); 
	// 创建文件格式写入对象实例, uncomment 
	//$objWriter = new PHPExcel_Writer_Excel5($objExcel);     // 用于其他版本格式 
	$objWriter = new PHPExcel_Writer_Excel2007($objExcel); // 用于 2007 格式 
	//$objWriter->setOffice2003Compatibility(true); 
	
	$sql = "select *, CONCAT('https://www.sqdish.com',path) as fullPath,  CONCAT('https://www.sqdish.com/dish/?id=',id) as fullid, CONCAT('https://www.sqdish.com/dish/toOrder.php?id=',id) as fullOrder from f_seller order by id desc";
	$sellerList = $db->get_all($sql);	
	
	//餐厅管理员 组装
	for($i=0;$i<count($sellerList);$i++){
		$sid = $sellerList[$i]["id"];
		$referee = $sellerList[$i]["referee"];
		
		$sql = "select * from f_user where sid = $sid";
		$user = $db->get_one($sql);
		
		if($user && isset($user['username']))
			$sellerList[$i]['user'] = $user['username'];
		else
			$sellerList[$i]['user'] = " ";
		
		$sql = "select * from f_user where id = $referee";
		$user = $db->get_one($sql);
		
		if($user && isset($user['username']))
			$sellerList[$i]['referee'] = $user['username'];
		else
			$sellerList[$i]['referee'] = " ";
	}
   
	exportExcel(
		$objExcel,//excel处理对象
		$objWriter,//excel输出对象
		'商家列表',
		'id,sellerName,tel,contacts,country,user,referee,fullPath,fullid,fullOrder',
		'id,商家名称,联系方式,联系人,国家,管理员,推荐人,首页,点餐页,结算页',
		'sheet',
		$sellerList
	);
?>