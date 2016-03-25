<?php
/*微信支付获取code
*created by sublime
*Author:Don Fisher
*Date:2016/2/17
*
*/
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	include_once dirname(__FILE__).'/../util/param.php';
	include_once dirname(__FILE__).'/../util/param.php';
	//引入支付配置文件
	require_once(dirname(__FILE__) . '/WapPay/server/init.php');
	session_start();
	
	date_default_timezone_set('PRC');
	
	$db = new DB();
	

	$app_id = "wx3ca7b87e788210b7";
	$redirect_url = "www.sqdish.com/dish/getOpenid.php";
	$app_secret = "38f3524d218b10520ee864dd3112386d";
	$code = WxpubOAuth::createOauthUrlForCode($app_id, $redirect_url, $more_info = false);
	$open_id = getOpenid($app_id, $app_secret, $code);




?>