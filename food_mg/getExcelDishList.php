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

	$sid = $_REQUEST["sid"];
	if($sid)
		$where = "where sid = $sid";
	else
		$where = "";
		
	if(isset($_SESSION["name"]))
		$name = $_SESSION["name"]."_";
	else
		$name = "";
	

	$sql = "select * from f_dish $where order by id desc";

	$sellerList = $db->get_all($sql);

   

	exportExcel(

		$objExcel,//excel处理对象

		$objWriter,//excel输出对象

		$name.'菜品列表',

		'id,dishNameCN,used,price',

		'id,菜品名字,点餐次数,单价',

		'sheet',

		$sellerList

	);

?>