<?php
header('Access-Control-Allow-Origin:*'); 
$act=$_GET['action'];
if (empty($act)) {
	$redirect_url = urlencode("http://www.sqdish.com/dish/test.php?action=code");
	$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3ca7b87e788210b7&redirect_uri=$redirect_url&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
	header('Location: ' . $url);
}
if ($act=='code') {
	if (isset($_GET['code'])) {
	$code = $_GET['code'];
	}
	if(!empty($code)){
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx3ca7b87e788210b7&secret=38f3524d218b10520ee864dd3112386d&code=$code&grant_type=authorization_code";
		$data = file_get_contents($url);
		$result = json_decode($data, true);
		if (isset($result['access_token'])) {
		            $accsee_token = $result['access_token'];
		            $openid = $result['openid'];
		            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$accsee_token&openid=$openid";
		            $data = file_get_contents($url);
		            $result = json_decode($data, true);
	        }
	}
}

header('Location: ' . "http://www.sqdish.com/dish/check.php?open_id='$result[openid]'");
?>