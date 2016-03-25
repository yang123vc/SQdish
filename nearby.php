<?php
	#Author:Don Fisher
	#date:2016-3-21

	include_once dirname(__FILE__).'/data/db/DB.php';


	$db = new DB();
	$lat = $_GET['latitude'];
	$lon = $_GET['longitude'];
	$raidus = 10000;
	/*
		 @param    getAround:根据提供的参数获取经纬度范围
		     	$lat:纬度
		    	$lon:经度
		    	$raidus:范围，单位：米
		return $minLat.$maxLat.$minLng.$maxLng;
	*/
	function getAround($lat,$lon,$raidus){
		$PI = 3.14159265;

		$latitude = $lat;
		$longitude = $lon;

		$degree = (24901*1609)/360.0;
		$raidusMile = $raidus;

		$dpmLat = 1/$degree;
		$radiusLat = $dpmLat*$raidusMile;
		$minLat = $latitude - $radiusLat;
		$maxLat = $latitude + $radiusLat;

		$mpdLng = $degree*cos($latitude * ($PI/180));
		$dpmLng = 1 / $mpdLng;
		$radiusLng = $dpmLng*$raidusMile;
		$minLng = $longitude - $radiusLng;
		$maxLng = $longitude + $radiusLng;
		return $minLat.'_'.$maxLat.'_'.$minLng.'_'.$maxLng;
	}
	//经纬度范围
	$around = getAround($lat,$lon,$raidus);
	$around_list = explode('_', $around);
	$minLat = $around_list[0];
	$maxLat = $around_list[1];
	$minLng = $around_list[2];
	$maxLng = $around_list[3];



	$preg = explode('.', $lat);



	//分页读取列表
	$pageSize = 60;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;

	//缩小范围减少查询量
	$sql = "select id,coordinates from f_seller where is_on=1 and pic<>'' and coordinates<>'' and coordinates like '$preg[0]%' limit $location,$pageSize";
	$list = $db->get_all($sql);
	foreach ($list as $key => $value) {
		$old = explode(',', $value['coordinates']);
		$lat_old = $old[0];
		$lng_old = $old[1];
		if (($lat_old <= $maxLat && $lat_old >= $minLat) && ($lng_old <= $maxLng && $lng_old >= $minLng)) {
			//基本
			$sql = "select id,sellerName,coordinates,pic,BH,click from f_seller where id='$value[id]'";
			$seller['base'] = $db->get_a($sql);
			//图标
			$sql = "select ser_name from f_dish_extra where sid='$value[id]' and is_on=1";
			$ser_name = $db->get_all($sql);	
				foreach ($ser_name as $k) {
					$sql = "select ser_icon from f_dish_extra where ser_name='$k[ser_name]' and sid=0 and is_on=1";
					$seller['ser_icon'][] = $db->get_a($sql);
				}
			//点餐次数
			$sql = "select count(*) from f_statistics where sid='$value[id]'";
			$sum= $db->get_a($sql);
			$seller['count'] =$sum*10;

			//获取评论星级
			$sql = "select star from f_comment where shop_id='$value[id]'";
			$star_list = $db->get_all($sql);
			$sum = 0;
			foreach ($star_list as $keys => $values) {
				$sum += $values['star'];
			}
			$count = count($star_list);
			$star_avg = ceil($sum/$count);
			//默认给4星
			if ($star_avg==0) {
				$star_avg = 4;
			}
			$seller['star_avg'] = $star_avg;
			$seller_list[] = $seller;
		}
	}


$agent = $_SERVER['HTTP_USER_AGENT'];  

if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

	include_once dirname(__FILE__)."/templates/wap/business_list_around.html";

}else {	
	print_r($seller_list);
	//include_once dirname(__FILE__)."/templates/business_list.html";

};

?>