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
	
	$sql = "select s.*,f.sellerName as name,f.country as country from f_statistics s left join f_seller f on s.sid = f.id";
	$statisticsList = $db->get_all($sql);
	
	exportExcel(
		$objExcel,//excel处理对象
		$objWriter,//excel输出对象
		'统计列表',
		'id,date,name,detail,price,country',
		'id,创建时间,餐厅名称,菜品详情,总价,国家',
		'sheet',
		$statisticsList
	);
?>