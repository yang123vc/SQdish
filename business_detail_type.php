<?php
//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	include_once dirname(__FILE__).'/inc/page.class.php';

	include_once dirname(__FILE__).'/inc/cart.php';
	include_once dirname(__FILE__).'/inc/json_class.php';
	include_once dirname(__FILE__).'/inc/cookie_class.php';

	$db = new DB();
	$sid=intval($_GET['sid']);//文章识别id
	if(empty($sid)){
		echo "sid err!!";
		echo "<script>location.href='/'</script>";
		exit;
	}
	/*
	//开始读取文章信息
	$sql = "select * from f_article where id = $tid ";		
	$r  = $db->get_one($sql);	
	if(empty($r)){
		echo "info err!!";
		echo "<script>location.href='/'</script>";
		exit;
	}
	$Categoryid=$r['articleCategory'];//栏目id
	//读取栏目信息
	$sql = "select * from f_article_category where id = $Categoryid ";		
	$cr  = $db->get_one($sql);//栏目信息	
	
	//读取该篇文章的相关推荐列表
	$sql = "select * from f_article_rcommend where articleId=$tid order by id desc";
	$Tlist=$db->get_all($sql);
//print_r($Tlist);


	//读取当前栏目推荐信息
	if(empty($Categoryid)){
		$where="where articleIst=1";
	}else{
		$where="where articleCategory=$Categoryid  and articleIst=1";
	}
//echo $where;	
	$sqls = "select * from f_article $where order by id desc limit 10";
	$talists  = $db->get_all($sqls);//栏目推荐信息
	*/
	//根据sid获取商家详细信息
	$sql = "select * from f_seller where id='$sid'";
	$detail = $db->get_one($sql);
		$sql = "select count(*) from f_statistics where sid='$sid'";
	$sum= $db->get_a($sql);
	$detail['count'] =$sum*10;
	//清除文本格式
	function cutstr_html($string,$sublen){
	          $string = strip_tags($string);
	          $string = preg_replace ('/\n/is', '', $string);
	          $string = preg_replace ('/ |　/is', '', $string);
	          $string = preg_replace ('/&nbsp;/is', '', $string);
	          preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);   
	          if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
	          else $string = join('', array_slice($t_string[0], 0, $sublen));
	          return $string;
   	 }
	$detail['intro'] = cutstr_html($detail['intro'],300);



	//根据sid 获取图标
	$sql ="select ser_name from f_dish_extra where sid='$sid' and is_on=1";
	$ser_name = $db->get_all($sql);
	$ser_icon =array();
	foreach ($ser_name as $v) {
		$sql = "select ser_icon from f_dish_extra where ser_name='$v[ser_name]' and sid=0 and is_on=1";
		$ser_icon[]= $db->get_a($sql);
	}
		//获取菜品分类
	$sql = "select * from f_dish_type where sid='$sid'";
	$List = $db->get_all($sql);
	//分类菜品数
	$typeList = array();
	foreach ($List as $v) {
		$sql = "select count(*) from f_dish where dtid='$v[id]'";
		$v['count'] = $db->get_a($sql);
		$typeList[] = $v;
	}
	//获取菜品分类id
	$dtid = $_GET['dtid']+0;
	$sql = "select typeNameCN from f_dish_type where id=$dtid";
	$typeName = $db->get_a($sql);


	$pageSize = 10;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	//根据分类获取菜品
	$sql = "select * from f_dish where dtid='$dtid' order by id desc limit $location,$pageSize";
	$type_dish=$db->get_all($sql);

	$sql = "select count(*) as count from f_dish where dtid='$dtid'";
	$temp = $db->get_one($sql);
	$count = $temp['count'];

	//全部数量
	$sql= "select count(*) from f_dish where sid ='$sid'";
	$sum = $db->get_a($sql);
	
		
	$pages = new page($pageSize,$count,$isw); 
	$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
	//print_r($type_dish);exit;



	$Cart = new Cart();
	$carinfo = $Cart->getMyCart();
	$goods_list = array();
	foreach( $carinfo['list'] as $shop_key=>$shop)
	{
		foreach( $shop['data'] as $goods_id=>$goods)
		{
			$goods_list[$goods_id]['id'] = $goods['id'];
			$goods_list[$goods_id]['count'] = $goods['count'];
			$goods_list[$goods_id]['name'] = $goods['name'];
			$goods_list[$goods_id]['shop_id'] = $shop_key;
			$goods_list[$goods_id]['cost'] = $goods['cost'];;
		}

	}
	$url = "http://".$_SERVER['HTTP_HOST'];
	$mid = 0;
	if(isset($_SESSION['user_info']))
	{
		$mid = $_SESSION['user_info']['id'];

	} else {
		$mid = 0;
	}

	$fid = $detail['id'];
	$fav_shop = $db->get_one("select * from f_favourite where status=0 and mid= ".intval($mid)." and fid=".$fid);
	$fav_flag = 0;
	if(empty($fav_shop))
	{
		$fav_flag = 0;
	} else {
		$fav_flag = 1;
	}
	include_once dirname(__FILE__)."/templates/business_detail_type.html";
?>