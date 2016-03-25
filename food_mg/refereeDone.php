<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	$where=" where 1=1 ";
	$db = new DB();	//搜索参数	if(isset($_REQUEST['key']))




	
	//分页参数
	$pageSize = 10;
	if(isset($_REQUEST['page']) && $_REQUEST['page'] != "")
		$page = $_REQUEST['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	
	//判断需要结算的用户，如果这星期没有推荐审核通过的，则不需要结算
	$one = 30.00;
	$endTime = time();//当前时间戳
	$startTime = $endTime - (24*7*60*60);//一周前时间戳
	//审核成功数
	
	//审核成功的id
	$sql = "select distinct referee from f_seller where is_on=1 and is_done=0 and reg_time between '$startTime' and '$endTime' order by referee desc";
	$referee = $db->get_all($sql);

	$List = array();
	foreach ($referee as $v) {
		$v['id'] = $v['referee'];
		$sql ="select username from f_user where id='$v[referee]'";
		$v['username'] = $db->get_a($sql);
		$sql = "select count(*) from f_seller where referee='$v[referee]' and is_on=1 and is_done=0";
		$rs = $db->get_a($sql);
		if ($rs) {
			$v['is_done']=0;
		}else{
			$v['is_done']=1;
		}
		$List[]=$v;
	}
	$List= array_slice($List,$location,$pageSize,true);
	$count = count($referee);
	
	//分页参数
	$totalPage = ceil($count/$pageSize);
	$start = $page - 2 > 0 ? $page - 2 : 1;
	$end = $page + 2 < $totalPage ? $page + 2 : $totalPage;
	$prev = $page - 1 < 1 ? -1 : $page - 1;
	$next = $page + 1 > $totalPage ? -1 : $page + 1;
	
	require("page.class.php");
	$pages = new page($pageSize,$count); 
	$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
							
							
	
	include_once dirname(__FILE__)."/templates/refereeDone.html";
?>