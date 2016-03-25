<?php
	define('ACC',true);
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	require(dirname(__FILE__).'/upload.class.php');
	require(dirname(__FILE__).'/image.class.php');
	//获取数据
	$data = array();
	$sid = $_SESSION[SID];
	$data['rt_name'] = trim($_POST['rt_name']);
	$data['is_on'] = $_POST['is_on']+0;
	$data['rt_id'] = $_POST['rt_id']+0;
	//实例化上传类
	if($_FILES['rt_pic']['error']==0){
		$upload = new upload();
		$path = $upload->up('rt_pic');
		$path = image::thumb($path,30,30);
		$data['rt_icon'] = '/'.$path;
	}
	//等比生成缩略图
	//echo  $path;exit;

	//验证数据合法性
	//实例化数据库操作,入库
	$db = new DB();
	$extra = $db->update('f_dish_rtype',$data,"sid=0 and rt_id='$data[rt_id]'");
	if($extra){
		echo <<<eof
		<script type="text/javascript">
			alert('编辑成功!');
			window.location.href='restaurantType.php';
		</script>
eof;
	}
		
?>