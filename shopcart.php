<?php
		include_once dirname(__FILE__).'/data/db/DB.php';
		ini_set("display_errors",1);
		$isw=0;//Î±¾²Ì¬ÅäÖÃ  1¿ªÆô 0¹Ø±Õ
		date_default_timezone_set('Asia/Shanghai');
		include_once dirname(__FILE__).'/data/db/DB.php';
		include_once dirname(__FILE__).'/inc/cart.php';
		include_once dirname(__FILE__).'/inc/json_class.php';
		include_once dirname(__FILE__).'/inc/cookie_class.php';
		include_once dirname(__FILE__).'/dish/function.php';
		include_once dirname(__FILE__).'/util/param.php';
		//print_r($_SESSION['cart']);exit();
		$db = new DB();
		$uid = $_SESSION['user_info']['id'];

		//--------------ÅÐ¶ÏÊÇ·ñµÇÂ¼-------------------//
		if($uid) {
			$user_info = $db -> get_one("select * from f_member where id=".$uid);
		}else{
			$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
			header("Location:".$jump_url);	
		}
		//--------------ÅÐ¶ÏÊÇ·ñµÇÂ¼-------------------//
		$carinfo = JSON::decode(str_replace(array('&','$'),array('"',','),ICookie::get('shoppingcart')));
		$Cart = new Cart();
		$carinfo = $Cart->getMyCart();
		
		//手机端和pc端购物车不互通
		if (empty($carinfo['list'])) {
			//手机端购物车
			$carinfo = $_SESSION['cart'];
			//当前汇率
			$exchangeRate = getValue('exchangeRate');
			$statisticalCode = getValue('statisticalCode');
			//购物车计算结果
			$result = getTotalResult($carinfo);
			
			$goods_list = array();
			if ($carinfo) {
			foreach ($carinfo as $k=> $v) {
				foreach ($v as $key => $goods) {
					$goods_id = substr(strstr($key, '_'),1);
					$list[$goods_id]['opId'] = $key;
					$list[$goods_id]['dtid'] = $k;
					$list[$goods_id]['id'] = $goods_id;
					$list[$goods_id]['count'] = $goods['num'];
					$list[$goods_id]['img'] = $db->get_a("select pic from f_dish where id='$goods_id'");
					$list[$goods_id]['name'] = $goods['nameCh'];
					$list[$goods_id]['shop_id'] = $db->get_a("select sid from f_dish where id='$goods_id'");
					$list[$goods_id]['cost'] = $goods['price'];
				}
				$goods_list = $list; 
			}
			}
		}else{


		$goods_list = array();
		
		$mid = 1;
		foreach( $carinfo['list'] as $shop_key=>$shop)
		{
			foreach( $shop['data'] as $goods_id=>$goods)
			{
				$goods_list[$goods_id]['id'] = $goods['id'];
				$goods_list[$goods_id]['count'] = $goods['count'];
				$goods_list[$goods_id]['img'] = $goods['img'];
				$goods_list[$goods_id]['name'] = $goods['name'];
				$goods_list[$goods_id]['shop_id'] = $shop_key;
				$goods_list[$goods_id]['cost'] = $goods['cost'];
				$fav_dish = $db->get_one("select * from f_favourite where status=0 and mid= " .$mid.  " and fid=".$goods['id']);
				if(empty($fav_dish))
				{
					$goods_list[$goods_id]['fav_flag'] = 0;

				}
				else
				{
					$goods_list[$goods_id]['fav_flag'] = 1;

				}


			}

		}
		}
		//判断客户端
	$agent = $_SERVER['HTTP_USER_AGENT'];  
	
	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

		include_once dirname(__FILE__)."/templates/wap/member/account-MyShopping.html";

	}else {	

		include_once dirname(__FILE__)."/templates/shopcart.html";

	};



?>