<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/sendMail.php';
	//print_r($_POST);
	//接收数据
	$id = $_POST['id']+0;
	$data['title'] = trim($_POST['emailTitle']);
	$data['addressor'] = trim($_POST['addressor']);//发件人
	$data['email_kind'] = $_POST['role']+0;
	$data['addressee'] = trim($_POST['addressee']); //收件人
	$data['content'] = $_POST['articleBody'];
	$data['w_time'] = time();
	//验证合法性
	if ($data['title']=='') {
		echo "<script>alert('标题不能为空')</script>";
		echo "<script>location.href='emailAdd.php'</script>";
		exit;
	}
	if ($data['addressor']=='') {
		$data['addressor'] = 'admin';
	}
	if ($data['email_kind']>1||$data['email_kind']<0) {
		echo "<script>alert('类型选择有误')</script>";
		echo "<script>location.href='emailAdd.php'</script>";
		exit;
	}
	if ($data['content']=='') {
		echo "<script>alert('内容不能为空')</script>";
		echo "<script>location.href='emailAdd.php'</script>";
		exit;
	}

	$db = new DB();
	if($db->update('f_email',$data,"id='$id'")){
		if($data['email_kind']==1){
			echo "<script>alert('邮件已保存')</script>";
			echo "<script>location.href='emailList.php'</script>";
		}else{
			//发送邮件
			$res = send_mail($data['addressee'],$data['title'],$data['content']);
			if($res){
				echo "<script>alert('已发送邮件')</script>";
				echo "<script>location.href='emailList.php'</script>";
			}else{
				echo "<script>alert('邮件发送失败')</script>";
				echo "<script>location.href='emailList.php'</script>";
			}
		}
	}
?>