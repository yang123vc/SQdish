<?php
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname ( __FILE__ ) . '/inc/page.class.php';
	include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
	$db = new DB();
	$uid = get_user_info();
	if($uid) {
			$user_info = $db -> get_one("select * from f_member where id=".$uid);
	}else{
			$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
			header("Location:".$jump_url);	
	}
	//我的评论
	//获取我的评论
	$sql = "SELECT * FROM f_comment WHERE member_id='$uid' ORDER BY id DESC";
	$list = $db->get_all($sql);
	$comList = array();
	foreach($list as $v){
		$sql ="SELECT id,sellerName,pic FROM f_seller WHERE id='$v[shop_id]'";
		$v['seller'] = $db->get_one($sql);
		$v['img'] = explode(',', $v['pic']);
		$comList[] = $v;
	}
	//print_r($comList);exit;

		$count = count($comList);
		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));	
	
	//print_r($comList);exit;
	$agent = $_SERVER['HTTP_USER_AGENT'];  
	
	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

		include_once dirname(__FILE__)."/templates/wap/member/account-MyEvaluation.html";

	}else {	

		include_once dirname(__FILE__).'/templates/member/comment.html';

	};





?>