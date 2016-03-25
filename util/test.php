<?php
	function get_between($input, $start, $end) {
		$substr = substr($input, strlen($start)+strpos($input, $start),
		(strlen($input) - strpos($input, $end))*(-1));
		return $substr;
	}

	$requesturl='http://apic.map.qq.com/translate/?type=3&points=114.041993,22.667204&output=jsonp&pf=jsapi';

	$ch=curl_init($requesturl);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$cexecute=curl_exec($ch);
	curl_close($ch);
	//$cexecute = get_between($cexecute,"(",")");
	$result = json_decode($cexecute,true);
	
	print_r($result['detail']['points'][0]['lng']);
	
?>