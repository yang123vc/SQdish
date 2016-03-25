<?php
//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
    include_once dirname(__FILE__).'/inc/cart.php';
	include_once dirname(__FILE__).'/inc/json_class.php';
	include_once dirname(__FILE__).'/inc/cookie_class.php';


// 判断登录后信息
	//$user_info = get_user_info();
   	//print_r($_SESSION);

	//print_r($user_info);
    
	$db = new DB();
   
	$sid=intval($_GET['sid']);//文章识别id
	if(empty($sid)){
		echo "sid err!!";
		echo "<script>location.href='/'</script>";
		exit;
	}


	//判断用户ip，同一个id在规定时间间隔不计数
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
	//获取菜品分类
	$sql = "select * from f_dish_type where sid='$sid'";
	$List = $db->get_all($sql);
	//分类菜品数
	$typeList = array();
	foreach ($List as $v) {
		$sql = "select count(*) from f_dish where dtid='$v[id]'";
		$v['count']=$db->get_a($sql);
		$typeList[] = $v;
	}
	$sql= "select count(*) from f_dish where sid ='$sid'";
	$sum = $db->get_a($sql);
	//根据分类获取菜品
	$type_dish = array();
	foreach ($List as $v) {
		$sql = "select * from f_dish where dtid='$v[id]' limit 4";
		$type_dish[$v['typeNameCN']]=$db->get_all($sql);
	}
	//添加菜品属性
	//print_r($type_dish);exit;

	//wap端推荐菜品,按点击量
	$sql = "SELECT * FROM f_dish WHERE sid = $sid ORDER BY used DESC LIMIT 5";
	$w_dish = $db->get_all($sql);

	//获取评论星级
	$sql = "select star from f_comment where shop_id='$sid'";
	$star_list = $db->get_all($sql);
	$sum = 0;
	foreach ($star_list as $key => $value) {
		$sum += $value['star'];
	}
	$count = count($star_list);
	$star_avg = ceil($sum/$count);
	//默认给4星
	if ($star_avg==0) {
		$star_avg = 4;
	}
    
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
   if(!empty($_SESSION['user_info']))
   {
	   $mid = $_SESSION['user_info']['id'];

   } else {
	   $mid = 0;
   }

   $fid = $detail['id'];
   $fav_shop = $db->get_one("select * from f_favourite where status=0 and mid= " .$mid.  " and fid=".$fid);
   $fav_flag = 0;
   if(empty($fav_shop))
   {
	   $fav_flag = 0;
   } else {
	   $fav_flag = 1;
   }



$agent = $_SERVER['HTTP_USER_AGENT'];  

if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

	include_once dirname(__FILE__)."/templates/wap/business_detail.html";

}else {	

	include_once dirname(__FILE__)."/templates/business_detail.html";	

};
?>