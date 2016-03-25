<?php
	session_start();
	
	$l = $_REQUEST['l'];
	
	$_SESSION['language'] = $l;
?>