<?php
	define('ACC',true);
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	require(dirname(__FILE__).'/upload.class.php');
	require(dirname(__FILE__).'/image.class.php');
	//获取数据
	//print_r($_POST);exit;
	$data = array();
	$data['ser_name'] = trim($_POST['ser_name']);
	$data['is_on'] = $_POST['is_on']+0;
	//echo $data['is_on'];
	$data['sid'] =0;
	//实例化上传类
	$upload = new upload();
	$path = $upload->up('ser_pic');
	//等比生成缩略图
	//echo  $path;exit;
	$path = image::thumb($path,30,30);
	$data['ser_icon'] = '/'.$path;

	//验证数据合法性
	if(empty($data['ser_name'])){
		echo "error1";
		exit;
	}
	if(!isset($data['is_on'])){
		echo 'error2';
		exit;
	} 
	if(empty($data['ser_icon'])){
		echo 'error3';
		exit;
	} 
	
	//实例化数据库操作,入库
	$db = new DB();
	$extra = $db->insert('f_dish_extra',$data);
	if($extra){
		echo <<<eof
		<script type="text/javascript">
			alert('添加成功!');
			window.location.href='dishExtra.php';
		</script>
eof;
	}
?>