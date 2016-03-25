<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../util/phpqrcode/qrcodeGenerator.php';
	
	$db = new DB();
	//print_r($_POST);exit();
	$action = $_REQUEST['action'];
	$id = $_REQUEST['id'];
	
	if($action == "del"){
		$sql = "delete from f_user where id = $id";
		$db->query($sql);
		
		echo "<script>location.href='UserList.php'</script>";
		exit;
	}
	//接收数据
	$username = trim($_POST['username']);

	$username = replace($username, "'", "\'");
	$sid = $_POST['sid']+0;
	$role = $_POST['role']+0;
	$password = trim($_POST['password']);
	$firstName = trim($_POST['firstName']);
	$surname = trim($_POST['surname']);
	$email = trim($_POST['email']);
	$phone = $_POST['phone']+0;
	$city = trim($_POST['city']);
	$reg_time = time();

	if(isset($_POST['position']) && $_POST['position'] != "")
		$position = $_POST['position']+0;
	else 
		$position = 0;
	
	$birthday = $_POST['birthday'];
	//验证数据合法性
	if (empty($username)) {
		echo "<script>alert('用户名不能为空')</script>";
		if ($_SESSION[USERSESSION]['role']==3) {
				echo "<script>location.href='sellerAdd.php'</script>";
		}else{
				echo "<script>location.href='UserAdd.php'</script>";
		}
		
		exit;
	}else if($action=='add'){
		$sql = "select count(*) from f_user where username='$username'";
		$rs = $db->get_a($sql);
		if ($rs!=0) {
		echo "<script>alert('用户名已存在')</script>";
				if ($_SESSION[USERSESSION]['role']==3) {
				echo "<script>location.href='sellerAdd.php'</script>";
		}else{
				echo "<script>location.href='UserAdd.php'</script>";
		}
		exit;
		}
	}
	//如果是由推荐人上传的信息，密码默认为111111
	if (!isset($password)) {
		echo "<script>alert('密码不能为空')</script>";
		if ($_SESSION[USERSESSION]['role']==3) {
				echo "<script>location.href='sellerAdd.php'</script>";
		}else{
				echo "<script>location.href='UserAdd.php'</script>";
		}
		echo "<script>location.href='UserAdd.php'</script>";	
		exit;
	}
	if ($role<0||$role>3) {
		echo "<script>alert('权限有误')</script>";
		if ($_SESSION[USERSESSION]['role']==3) {
				echo "<script>location.href='sellerAdd.php'</script>";
		}else{
				echo "<script>location.href='UserAdd.php'</script>";
		}
		exit;
	}

	if (!isset($sid)) {
		echo "<script>alert('请先填写商家信息')</script>";
		if ($_SESSION[USERSESSION]['role']==3) {
				if(acttion=='add'){echo "<script>location.href='sellerAdd.php'</script>";}
				if(acttion=='update'){echo "<script>location.href='sellerUpdate.php'</script>";}			
		}else{
				if(acttion=='add'){echo "<script>location.href='sellerAdd.php'</script>";}
				if(acttion=='update'){echo "<script>location.href='userUpdate.php'</script>";}	
		}
		exit;
	}
	if($action == "add"){
		$sql = getAddSql($role,$username,$sid,$password,$firstName,$surname,$position,$birthday,$email,$phone,$city,$reg_time);
		$db->query($sql);
	}else if($action == "update"){
		$sql = getUpdateSql($role,$username,$sid,$password,$id,$firstName,$surname,$position,$birthday,$email,$phone,$city,$reg_time);
		$db->query($sql);
	}
	if ($_SESSION[USERSESSION]['role']==3) {
				echo "<script>alert('上传成功，正在审核......')</script>";
				echo "<script>location.href='sellerAdd.php'</script>";
		}else{
				echo "<script>location.href='UserList.php'</script>";
		}

	
	function getAddSql($role=0,$username,$sid,$password,$firstName,$surname,$position,$birthday,$email,$phone,$city,$reg_time){
		$sql = "";
	
		if($role == 1){
			$sql = "insert into f_user(username,password,role,reg_time) values('$username','$password','$role','$reg_time')";
		}else if($role == 2){
			$sql = "insert into f_user(username,password,sid,role,firstName,surname,city,position,birthday,email,phone,reg_time) values('$username','$password',$sid,$role,'$firstName','$surname','$city','$position','$birthday','$email','$phone','$reg_time')";
		}else if($role == 3){
			$sql = "insert into f_user(username,password,role,firstName,surname,email,phone,city,reg_time) values('$username','$password','$role','$firstName','$surname','$email','$phone','$city','$reg_time')";
		}
		
		return $sql;
	}	
	
	function getUpdateSql($role=0,$username,$sid,$password,$id=-1,$firstName,$surname,$position,$birthday,$email,$phone,$city){
		$sql = "";
	
		if($role == 1){
			$sql = "update f_user set username = '$username',password = '$password',role = $role where id = $id";
		}else if($role == 2){
			$sql = "update f_user set username = '$username',password = '$password',sid = $sid,role = $role,firstName='$firstName',surname='$surname',city='$city',position=$position,birthday='$birthday' where id = $id";
		}else if($role == 3){
			$sql = "update f_user set username = '$username',password = '$password',role = $role,firstName ='$firstName',surname ='$surname',email='$email',phone='$phone',city='$city' where id = $id";
		}
		
		return $sql;
	}	
?>