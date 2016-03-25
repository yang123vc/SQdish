<?php
//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';

	include_once dirname(__FILE__).'/inc/cart.php';
	include_once dirname(__FILE__).'/inc/json_class.php';
	include_once dirname(__FILE__).'/inc/cookie_class.php';
	$db = new DB();
	//$sid=intval($_GET['sid']);//文章识别id
	//if(empty($sid)){
	//	echo "sid err!!";
	//	echo "<script>location.href='/'</script>";
	//	exit;
	//}
	//print_r($type_dish);exit;
	//根据sid获取商家信息
	$id=intval($_REQUEST['id']);
	$sid=intval($_REQUEST['sid']);
	$dtid=intval($_REQUEST['dtid']);

	if(empty($sid)){
		echo "sid err!!";
		echo "<script>location.href='/'</script>";
		exit;
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

	//获取菜品详细信息
	$sql = "select * from f_dish where id='$id'";
	$dishDetail = $db->get_one($sql);
	//获取菜品的选项信息
	$sql = "select * from f_dish_options where did='$id'";
	$Options = $db->get_all($sql);
	$dishOptions =array();
	foreach($Options as $v){
		$v['detail']=explode(',',$v['optionCN']);
		$v['price']=explode(',',$v['optionPrice']);					
		$dishOptions[] = $v;
	}
	//print_r($dishOptions);exit;
	//随机获取更多美食
	$sql = "select * from f_dish where sid='$sid' order by rand() limit 4";
	$dishRand = $db->get_all($sql);
	//最近浏览商品
	session_start();
	if(isset($_SESSION['recently'])){
		array_push($_SESSION['recently'],$id);
	}else{
		$_SESSION['recently']=array($id);
	}
	$id_List =array_reverse(array_unique($_SESSION['recently']));
	$idList = array_slice($id_List,0,4);
	foreach($idList as $v){
		$sql = "select * from f_dish where id=$v";
		$dishRecently[]=$db->get_one($sql);
	}

	$Cart = new Cart();
	$carinfo = $Cart->getMyCart();
   // print_r($carinfo);
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
	//print_r(count($goods_list));
	$url = "http://".$_SERVER['HTTP_HOST'];
	if(!empty($_SESSION['user_info']))
	{
		$mid = $_SESSION['user_info']['id'];

	} else {
		$mid = 0;
	}

	$fid = $sid;
	$fav_shop = $db->get_one("select * from f_favourite where status=0 and mid= " .$mid.  " and fid=".$fid);
	$fav_flag = 0;
	if(empty($fav_shop))
	{
		$fav_flag = 0;
	} else {
		$fav_flag = 1;
	}

   $fav_dish_flag = 0;
   $fav_dish = $db->get_one("select * from f_favourite where status=0 and mid= " .$mid.  " and fid=".$id);
   if(empty($fav_dish))
   {
	   $fav_dish_flag = 0;
   } else {
	   $fav_dish_flag = 1;
   }





	include_once dirname(__FILE__)."/templates/dish_detail.html";
?>