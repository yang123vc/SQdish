<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/sendMail.php';
	
	$db = new DB();
	$data = array();
	$id = $_GET['id']+0;
	$page = $_GET['page']+0;
	$act = $_GET['act'];
	if($act=='fail'){
		$data['is_on'] = -1;
			$data['_error'] = trim($_GET['error']);
		$rs = $db->update('f_seller',$data,"id='$id'");
		if ($rs) {
			echo "<script>alert('未通过原因已提交')</script>";
			echo "<script>location.href='sellerList.php'</script>";
		}
	}else{
		$data['is_on'] = 1;
		$rs = $db->update('f_seller',$data,"id='$id'");
		if ($rs) {
			//获取目标邮箱
			$sql = "select email from f_user where sid='$id'";
			$email = $db->get_a($sql);
			//获取邮件标题和内容
			$sql = "select title from f_email where id=1";
			$title = $db->get_a($sql);

			$sql = "select content from f_email where id=1";
			$content = $db->get_a($sql);

			$res = send_mail($email,$title,$content);
			if($res){
				echo "<script>alert('审核通过，已发送邮件')</script>";
				echo "<script>location.href='sellerList.php?page=$page'</script>";
			}else{
				echo "<script>alert('邮件发送失败')</script>";
				echo "<script>location.href='sellerList.php?page=$page'</script>";
			}

		}
	}




?>	