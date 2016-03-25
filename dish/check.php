<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	include_once dirname(__FILE__).'/../util/param.php';
	session_start();
	
	$db = new DB();
	
	$id = $_REQUEST['id'];
	$cart = "";
	$oCart = "";

	if(isset($_SESSION['cart']))
		$cart = $_SESSION['cart'];
		
	$exchangeRate = getValue('exchangeRate');
	$statisticalCode = getValue('statisticalCode');

	$result = getTotalResult($cart);
	//判断登陆
	if (isset($_SESSION['user_info'])) {
		$user_info = $_SESSION['user_info'];
		//判断是否微信登陆
		$user_id = $user_info['id'];
		$sql = "select open_id from f_member where id='$user_id'";
		$res = $db->get_a($sql);
	}
	//print_r($result);exit();

	//$oResult = getTotalResult($oCart);	
	if ($id) {
		//是否支持在线支付
		$online_pay = $db->get_a("select online_pay from f_seller where id='$id'");
		include_once dirname(__FILE__)."/check.html";		
	}else{
		foreach ($result['classNum'] as $key => $value) {
			$type_id[] = $key;
		}
		$tid = $type_id[0];
		$id = $db->get_a("select sid from f_dish_type where id='$tid'");
		$online_pay = $db->get_a("select online_pay from f_seller where id='$id'");
		include_once dirname(__FILE__)."/user_shop_cart.html";
	}
			
?>