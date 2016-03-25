<?php
	define('ACC',true);
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	require(dirname(__FILE__).'/upload.class.php');
	require(dirname(__FILE__).'/image.class.php');
	//获取数据
	$data = array();
	$sid = $_SESSION[SID];
	$data['ser_name'] = trim($_POST['ser_name']);
	$data['is_on'] = $_POST['is_on']+0;
	$data['ser_id'] = $_POST['ser_id']+0;
	//实例化上传类
	if ($_FILES['ser_pic']['error']==0) {
		$upload = new upload();
		$path = $upload->up('ser_pic');
		//等比生成缩略图
		//echo  $path;exit;
		$path = image::thumb($path,30,30);
		$data['ser_icon'] = '/'.$path;# code...
	}
	if(empty($data['ser_name'])){
		echo "<script>alert('名称不能为空');</script>";
		echo "<script>location.href='dishExtra.php'</script>";
		exit;
	}
	//实例化数据库操作,入库
	$db = new DB();
	$extra = $db->update('f_dish_extra',$data,"sid=0 and ser_id='$data[ser_id]'");
	if($extra){
		echo <<<eof
		<script type="text/javascript">
			alert('编辑成功!');
			window.location.href='dishExtra.php';
		</script>
eof;
	}
		
?>