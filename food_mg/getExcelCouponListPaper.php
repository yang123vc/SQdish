<?php
include_once dirname(__FILE__)."/../inc/Classes/PHPExcel.php";
include_once dirname(__FILE__).'/../data/db/DB.php';
date_default_timezone_set('PRC');
//创建对象
$excel = new PHPExcel();
$db = new DB();

$sid =  $_GET['sid'];
$sql = "select id,cid,code,status,usetime from f_coupon_paper where sid = '$sid'";

$coupon_list = $db->get_all($sql);

foreach ($coupon_list as $value) {
	$value['couponName'] = $db->get_a("select couponNameCN from f_coupon where id='$value[cid]'");
	switch ($value['status']) {
		case '0':
			$value['status'] = '未使用';
			break;
		case '1':
			$value['status'] = '已使用';
			break;
		case '2':
			$value['status'] = '已过期';
			break;	
		case '3':
			$value['status'] = '已删除';
			break;
		default:
			$value['status'] = '错误';
			break;
	}
	if ($value['usetime']==0) {
		$value['usetime'] = "——";
	}else{
		$value['usetime'] = date('Y-m-d H:i:s',$value['usetime']); 
	}
	$coupon_lists[] = $value;	
}

//print_r($coupon_lists);exit;
/*-------------------填充表头----------------------*/
//Excel表格式,这里简略写了8列
$letter = array('A','B','C','D','E','F');
//表头数组
$tableheader = array('ID','cid','优惠券验证码','状态','使用时间','优惠券名称');
//填充表头信息
for($i = 0;$i < count($tableheader);$i++) {
	$excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
}


/*-------------------填充表格----------------------*/


//填充表格信息

for ($i = 2;$i <=count($coupon_lists) + 1;$i++) {
	$j = 0;
	foreach ($coupon_lists[$i - 2] as $key=>$value) {
		$excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value");
		$j++;
	}
}
/*
for ($i = 2;$i <=count($coupon_list) + 1;$i++) {
	$j = 0;
	for ($j;$j<count($coupon_list[$i]);$j++) {
		$excel->getActiveSheet()->setCellValue("$letter[$j]$i","$coupon_list[$i - 2][$j]");
	}
}
*/

/*-------------------创建Excel输入对象----------------------*/
$write = new PHPExcel_Writer_Excel5($excel);

header("Pragma: public");

header("Expires: 0");

header("Cache-Control:must-revalidate, post-check=0, pre-check=0");

header("Content-Type:application/force-download");

header("Content-Type:application/vnd.ms-execl");

header("Content-Type:application/octet-stream");

header("Content-Type:application/download");;

header('Content-Disposition:attachment;filename="纸质优惠券列表.xls"');

header("Content-Transfer-Encoding:binary");

$write->save('php://output');


?>