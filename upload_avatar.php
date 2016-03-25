<?php
/**
 * 功能：会员头像设置
 * @author prh
 */
include_once dirname(__FILE__).'/data/db/DB.php';
try {
	$savePath = './upload/user_photo/';  //图片存储路径
	$savePicName = time();  //图片存储名称
	
	
	$file_src = $savePath.$savePicName."_src.jpg";
	$filename162 = $savePath.$savePicName."_162.jpg"; 
	$filename48 = $savePath.$savePicName."_48.jpg"; 
	$filename20 = $savePath.$savePicName."_20.jpg";    
	
	$src=base64_decode($_POST['pic']);
	$pic1=base64_decode($_POST['pic1']);   
	$pic2=base64_decode($_POST['pic2']);  
	$pic3=base64_decode($_POST['pic3']);  
	
	if($src) {
		file_put_contents($file_src,$src);
	}
	
	file_put_contents($filename162,$pic1);
	file_put_contents($filename48,$pic2);
	file_put_contents($filename20,$pic3);
	
	$islogin = 1;
	if($islogin) {
		$uid = 1;
		//更新会员表数据
		$db = new DB();
		$pic162 = '/upload/user_photo/'.$savePicName."_162.jpg"; 
		$pic48 = '/upload/user_photo/'.$savePicName."_48.jpg"; 
		$pic20 = '/upload/user_photo/'.$savePicName."_20.jpg"; 
		$new_array = array('pic162'=>$pic162,'pic48'=>$pic48,'pic20'=>$pic20);
		$db->update( 'f_member',$new_array,'id = '.$uid);
		$rs['status'] = 1;
		$rs['picUrl'] = $savePath.$savePicName;
	} else {
		$rs['status'] = -1;
		$rs['picUrl'] = '';
	}	
} catch (Exception $e) {
	$rs['status'] = -1;
	$rs['picUrl'] = '';
}
print json_encode($rs);

?>
