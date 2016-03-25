<?php
ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
    include_once dirname(__FILE__).'/inc/cart.php';
	include_once dirname(__FILE__).'/inc/json_class.php';
	include_once dirname(__FILE__).'/inc/cookie_class.php';
	$db = new DB();

    $sid = $_POST['sid'];


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
    $carinfo = JSON::decode(str_replace(array('&','$'),array('"',','),ICookie::get('shoppingcart')));
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
            $goods_list[$goods_id]['cost'] = $goods['cost'];
	    }

    }

	include_once dirname(__FILE__)."/templates/smallcart.html";
   

?>