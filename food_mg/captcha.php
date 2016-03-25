<?php
	include_once dirname(__FILE__).'/../util/validateCode.class.php';
	session_start();
	
	$code=new ValidateCode();
 
	ob_clean();
	$code->doimg();  
	$_SESSION["code"]=$code->getCode();
?>