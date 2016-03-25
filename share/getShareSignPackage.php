<?php
	require_once "jssdk.php";
	
	$url = $_POST['url'];
	$jssdk = new JSSDK("wx1bb85f3029d429d5", "b08fe28efa2973d0c0a8d1ae19f76276 ");
	$signPackage = $jssdk->GetSignPackage($url);

	echo json_encode($signPackage);
?>