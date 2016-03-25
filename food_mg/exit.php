<?php
	include_once dirname(__FILE__).'/../data/constant.php';
	
	session_start();
	
	if(isset($_SESSION[USERSESSION])){
		unset($_SESSION[USERSESSION]);
	}
	
	if(isset($_SESSION[SID])){
		unset($_SESSION[SID]);
	}
	
	if(isset($_SESSION["cart"])){
		unset($_SESSION["cart"]);
	}
	
	if(isset($_SESSION["name"])){
		unset($_SESSION["name"]);
	}
	
	echo "<script>location.href='sellerList.php'</script>";
?>