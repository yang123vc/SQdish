<?php
	include_once dirname(__FILE__).'/../data/constant.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	session_start();
	
	if(isset($_SESSION['language'])){
		if($_SESSION['language'] == "en")
			include_once dirname(__FILE__).'/../inc/en.php';
		
		if($_SESSION['language'] == "thai")
			include_once dirname(__FILE__).'/../inc/thai.php';
		
		if($_SESSION['language'] == "cn")
			include_once dirname(__FILE__).'/../inc/cn.php';
	}else{
		$_SESSION['language'] = "en";
	}
	
	$db = new DB();

	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$validateCode = $_POST['validateCode'];
	$vCode = $_SESSION["code"];
	
	if($validateCode && strcasecmp($validateCode,$vCode) == 0){
		$sql = "select * from f_user where username = '$username' and password = '$password'";
		$user = $db->get_one($sql);
		
		if($user && $user['role'] == 1){
			$_SESSION[USERSESSION] = $user;

			echo "<script>location.href='sellerList.php'</script>";
		}else if($user && $user['role'] == 2){
			$_SESSION[USERSESSION] = $user;
			$_SESSION[SID] = $user['sid'];			
			echo "<script>location.href='dishList.php'</script>";
		//地推人员权限
		}else if($user && $user['role'] == 3){
			$_SESSION[USERSESSION] = $user;			
			echo "<script>location.href='index.php'</script>";
		}else{
			if(isset($_SESSION[USERSESSION]))
				unset($_SESSION[USERSESSION]);
			
			if(isset($_SESSION[SID]))
				unset($_SESSION[SID]);

			$error = $login_err_text;
			include_once dirname(__FILE__)."/templates/Login.html";
		}
	}else{
		if(isset($_SESSION[USERSESSION]))
			unset($_SESSION[USERSESSION]);
		
		if(isset($_SESSION[SID]))
			unset($_SESSION[SID]);
		
		$error = $code_err_text;
		include_once dirname(__FILE__)."/templates/Login.html";
	}
?>