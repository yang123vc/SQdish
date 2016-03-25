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
	

	$sql = "select * from f_member_coupon $where order by status desc, id desc";

	$couponList = $db->get_all($sql);
	$size_coupon = count($couponList);
	for ($i=0; $i < $size_coupon; $i++) { 
		$cname[$i] = $db->get_one("select couponNameCN, couponNameTHAI, pic from f_coupon where id = ".$couponList[$i]['cid']);
		$couponList[$i]['couponname'] = $cname[$i]['couponNameCN'].'('.$cname[$i]['couponNameTHAI'].')';
		$couponList[$i]['statusname'] = status_to_name($couponList[$i]['status']);
	}
	function status_to_name($status){
		if ($status == 0) {
			$statusname = '未使用';
		}
		elseif ($status == 1) {
			$statusname = '已使用';
		}
		return $statusname;
	}

	exportExcel(

		$objExcel,//excel处理对象

		$objWriter,//excel输出对象

		$name.'优惠券使用情况列表',

		'id,couponname,buytime,usetime,statusname',

		'id,优惠券名称,购买时间,使用时间,状态',

		'sheet',

		$couponList

	);

?>