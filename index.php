<?php

ini_set("display_errors",1);

	$isw=0;//伪静态配置  1开启 0关闭

	date_default_timezone_set('Asia/Shanghai');

	include_once dirname(__FILE__).'/data/db/DB.php';

	include_once dirname(__FILE__).'/inc/articleFunction.php';

	$db = new DB();

//首页动态程序




//print_r($_SESSION);exit();
//推荐文章读取

$where="where articleIst=1 ";

$sql = "select * from f_article $where order by id desc limit 6";

$Tarticlelist = $db->get_all($sql);


//首页推荐店铺
$sql = "SELECT id,pic,sellerName,intro FROM f_seller ORDER BY click DESC LIMIT 0,9";
$List_one = $db->get_all($sql);
$sql = "SELECT id,pic,sellerName,intro FROM f_seller ORDER BY click DESC LIMIT 10,9";
$List_two = $db->get_all($sql);
foreach($List_one as $v){
	$v['intro'] = cutstr_html($v['intro'],300);
	$sellerList_one[]=$v;
}
foreach($List_two as $v){
	$v['intro'] = cutstr_html($v['intro'],300);
	$sellerList_two[]=$v;
}
//清除html标签
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

//首页推荐菜品
$sql = "SELECT id,pic,dishNameCN,dishNameTHAI,dishIntroCN FROM f_dish ORDER BY used DESC LIMIT 0,9";
$dList_one = $db->get_all($sql);
$sql = "SELECT id,pic,dishNameCN,dishNameTHAI,dishIntroCN FROM f_dish ORDER BY used DESC LIMIT 10,9";
$dList_two = $db->get_all($sql);
foreach ($dList_one as $v) {
	$sql ="SELECT sid FROM f_dish WHERE id='$v[id]'";
	$v['sid'] = $db->get_a($sql);
	$dishList_one[] = $v; 
}
foreach ($dList_two as $v) {
	$sql ="SELECT sid FROM f_dish WHERE id='$v[id]'";
	$v['sid'] = $db->get_a($sql);
	$dishList_two[] = $v; 
}
//手机推荐商家
$sql = "SELECT id,pic,sellerName,intro FROM f_seller ORDER BY click DESC LIMIT 0,4";
$w_List = $db->get_all($sql);
foreach($List_two as $v){
	$v['intro'] = cutstr_html($v['intro'],100);
	$w_sellerList[]=$v;
}

//地图定位服务系统

//获取栏目

$sql = "select rt_name from f_dish_rtype where sid=0 and is_on=1";

$rt_name = $db->get_all($sql);

//print_r($rt_name);exit;

$sidList = array();

//根据栏目获取sid

foreach ($rt_name as $v) {

	$sql = "select sid from f_dish_rtype where  rt_name='$v[rt_name]' and sid<>0 and is_on=1 order by rt_id desc limit 12";

	$sidList[] = $db->get_all($sql);

}



//根据sid获取商家详细信息

$sellerList = array();



//print_r($sidList);exit;

foreach ($sidList as $v) {

	$list = array();//初始化

	foreach ($v as $k) {

		$sql ="select * from f_seller where id='$k[sid]'";

		$k['seller']=$db->get_one($sql);

		$list[]=$k;

	}

	$sellerList[] =$list;

	

} 

//获取热门餐厅

$sql = "select * from f_seller where is_on=1 order by click desc limit 12";

$hotList = $db->get_all($sql);





//print_r($sidList);exit;
$port = $_GET['port'];
$agent = $_SERVER['HTTP_USER_AGENT'];  

if ($port&&$port=='pc') {
	include_once dirname(__FILE__)."/templates/index_google.html";	
	exit;
}
if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

	include_once dirname(__FILE__)."/templates/wap/index.html";

}else {	

	include_once dirname(__FILE__)."/templates/index_google.html";	

};



?>