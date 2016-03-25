<?php
	
	//登陆检查
	function if_login(){
		if (!isset($_SESSION['user_info'])) {
			 header("location:/login.php");
			 exit;
		}
	}
?>