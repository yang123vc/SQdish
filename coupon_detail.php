<?php
//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	$db = new DB();
	//$sid=intval($_GET['sid']);//文章识别id
	//if(empty($sid)){
	//	echo "sid err!!";
	//	echo "<script>location.href='/'</script>";
	//	exit;
	//}
	//print_r($type_dish);exit;
	//根据sid获取商家信息
	$cid=intval($_REQUEST['cid']);

	if(empty($cid)){
		echo "id err!!";
		echo "<script>location.href='/'</script>";
		exit;
	}
	if ($_SESSION['user_info']) {
		$mid = $_SESSION['user_info']['id'];
	}else{
		$mid = 1;
	}
	//获取菜品详细信息
	$sql = "select * from f_coupon where id='$cid'";
	$couponDetail = $db->get_one($sql);
	$sid = $couponDetail['sid'];
	//获取已售数量
	$sql = "select count(1) as num from f_coupon_tmp where cid='$cid' and status = 1";
	$used = $db->get_one($sql);
	$couponDetail['used'] = $used['num'];
	//根据sid获取商家详细信息
	$sql = "select * from f_seller where id='$sid'";
	$detail = $db->get_one($sql);
	//点餐次数
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
	//随机获取更多美食
	$sql = "select * from f_dish where sid='$sid' order by rand() limit 4";
	$dishRand = $db->get_all($sql);
	//更多优惠券
	$sql = "select * from f_coupon order by rand() limit 4";
	$couponRand = $db->get_all($sql);
	//对应商家
	$size_couponRand = count($couponRand);
	for ($i=0; $i < $size_couponRand; $i++) { 
		$sql = "select sellerName from f_seller where id = $sid";
		$seller[$i] = $db->get_one($sql);
		$couponRand[$i]['sellerName'] = $seller[$i]['sellerName'];
	}
	//获取会员待支付优惠券数量
	$sql = "select count(1) as num from f_coupon_tmp where mid= '$mid' and status = 0";//会员idw为登陆后session中的会员id
	$couponCount = $db->get_one($sql);

	//待支付优惠券
	$sql = "select id,couponNameCN,price from f_coupon where sid='$sid' and status = 0";
	$list = $db->get_all($sql);
	foreach ($list as $keys => $value) {
		$value['num'] = $db->get_a("select count(*) from f_coupon_tmp where mid='$mid' and sid='$sid' and cid ='$value[id]' and status =0");
		if ($value['num']!=0) {
			$coupon_list[] = $value;
			$total += $value['price']*$value['num'];
		}		
	}  
	//print_r($coupon_list);exit;
	//收藏
	   $url = "http://".$_SERVER['HTTP_HOST'];
	   if(!empty($_SESSION['user_info']))
	   {
		   $mid = $_SESSION['user_info']['id'];

	   } else {
		   $mid = 1;
	   }

	   $fav_coupon = $db->get_one("select * from f_favourite where status=0 and mid= " .$mid.  " and fid=".$cid);
	   $fav_flag_coupon = 0;
	   if(empty($fav_coupon))
	   {
		   $fav_flag_coupon = 0;
	   } else {
		   $fav_flag_coupon = 1;
	   }
	   $fav_shop = $db->get_one("select * from f_favourite where status=0 and mid= " .$mid.  " and fid=".$sid);
	   //收藏商户
	 	$fav_flag_shop = 0;
	   if(empty($fav_shop))
	   {
		   $fav_flag_shop = 0;
	   } else {
		   $fav_flag_shop = 1;
	   }

	include_once dirname(__FILE__)."/templates/coupon_detail.html";
?>