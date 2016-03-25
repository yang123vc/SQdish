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
	//我的收藏
	//商品
	$act = isset($_GET['act'])?$_GET['act']:'dish';
	$pageSize = 10;
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	if ($act=='dish') {
		$sql = "SELECT fid From f_favourite WHERE mid='$uid' AND cat=2 AND status=0";
		$fid_list = $db->get_all($sql);
		//收藏的菜品
		$dishList = array();
		foreach($fid_list as $v){
			$sql ="SELECT id,sid,pic,dishNameCN,price,used FROM f_dish WHERE id='$v[fid]'";
			$dishList[] = $db->get_one($sql);
		}

		$count = count($dishList);

		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
	$agent = $_SERVER['HTTP_USER_AGENT'];  
	
	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

		include_once dirname(__FILE__)."/templates/wap/member/account-MyCollection.html";

	}else {	

		include_once dirname(__FILE__).'/templates/member/dish_favourite.html';

	};
	

	}else {
		$sql = "SELECT fid From f_favourite WHERE mid='$uid' AND cat=1 AND status=0";
		$fid_list = $db->get_all($sql);
		//收藏的商家
		$List = array();
		foreach ($fid_list as $v) {
			$sql = "SELECT id,sellerName,pic FROM f_seller WHERE id='$v[fid]'";
			$List[] = $db->get_one($sql);
		}
		//组装图标
		
		$sellerList = array();
		foreach ($List as $v) {
			$sql = "select ser_name from f_dish_extra where sid='$v[id]' and is_on=1";
			$ser_name = $db->get_all($sql);	
			foreach ($ser_name as $k) {
				$sql = "select ser_icon from f_dish_extra where ser_name='$k[ser_name]' and sid=0 and is_on=1";
				$v['ser_icon'][] = $db->get_a($sql);
			}
			$sql = "select count(*) from f_statistics where sid='$v[id]'";
			$sum= $db->get_a($sql);
			$v['count'] =$sum*10; 
			$sellerList[]=$v;   
		}
		//print_r($sellerList);exit;
		$count = count($sellerList);
		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));	
	$agent = $_SERVER['HTTP_USER_AGENT'];  
	
	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

		include_once dirname(__FILE__)."/templates/wap/member/account-MyCollection_seller.html";

	}else {	

		include_once dirname(__FILE__).'/templates/member/seller_favourite.html';

	};
	
	}






?>