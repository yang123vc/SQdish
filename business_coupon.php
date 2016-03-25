<?php
//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	$db = new DB();
	$sid=intval($_GET['sid']);//文章识别id
	if(empty($sid)){
		echo "sid err!!";
		echo "<script>location.href='/'</script>";
		exit;
	}
	//print_r($_SESSION);exit();
	if ($_SESSION['user_info']) {
		$mid = $_SESSION['user_info']['id'];
	}else{
		$mid = 1;
	}
	
	//判断用户ip，同一个id在规定时间间''隔不计数
	$ip=getIP();
	$sql = "select * from f_seller_count where ip = '$ip' and sid='$sid'";		
	$r  = $db->get_one($sql);
	//print_r($r);
	if((time()-$r['click_time'])>3600){
		$sql = "update f_seller set click=click+1 where id='$sid'";
		$db->query($sql);
		$data['click_time']=time();
		if($r){			
		$r  = $db->update('f_seller_count',$data," ip='$ip' and sid=$sid ");
		}else{
			$data['ip']=$ip;
			$data['sid']=$sid;
			$r  = $db->insert('f_seller_count',$data);
		}
		
	}
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
	//获取商家优惠券
	$sql = "select * from f_coupon where sid='$sid' and type =1";
	$couponList = $db->get_all($sql);

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

	 //收藏商户
	$fav_shop = $db->get_one("select * from f_favourite where status=0 and mid= " .$mid.  " and fid=".$sid);
	
	 $fav_flag_shop = 0;
	   if(empty($fav_shop))
	   {
		   $fav_flag_shop = 0;
	   } else {
		   $fav_flag_shop = 1;
	   }
	//print_r($coupon_list);exit;
	    $url = "http://".$_SERVER['HTTP_HOST'];
	include_once dirname(__FILE__)."/templates/business_coupon.html";
?>