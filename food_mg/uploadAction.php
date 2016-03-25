<?php
	include '../inc/isLogin.config.php';
	include '../util/cut.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	
	$db = new DB();

	if(isset($_GET['act']))
		$action = $_GET['act'];
	else
		$action = '';
		
	if($action=='delimg'){
		/*$filename = $_POST['imagename'];
		if(!empty($filename)){
			unlink('files/'.$filename);
			echo '1';
		}else{
			echo '删除失败.';
		}*/
	}else if($action=='cutimg'){
		$width = $_POST['w'];
		$height = $_POST['h']-14;
		$x = $_POST['x'];
		$y = $_POST['y'];
		
		$pic = $_SESSION['coverPath'];
		$truePath = $_SESSION['coverTruePath'];
		$flag = 1;
		$msg = "失败";
		
		$type = strrchr($pic, '.');
		$picName = substr($pic, 0, -strlen($type)); 
		
		$picArr = getimagesize($truePath);
		$trueWidth = $picArr[0];
		$trueHeight = $picArr[1];
		
		try{
			thumb($truePath,$width*$trueWidth/320,$height*$trueWidth/320,0,0,$x*$trueWidth/320,$y*$trueWidth/320);
		}catch(Exception $e) {
			$flag = 0;
			exit();  
		}
		
		$pic = $picName."_crop".$type;
		$_SESSION['coverPath'] = $pic;
		
		$arr = array(
			'flag'=>$flag,
			'pic'=>$pic,
			'msg'=>$msg
		);
		echo json_encode($arr);
	}else{
		if(!$_FILES) exit();
		
		$filename = date('His',time());
		$date =  date('Ymd',time());;
		
		//创建目录
		$path = dirname(__FILE__). DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."plupload". DIRECTORY_SEPARATOR .$date;
		if(!is_dir($path))
			mkdir($path);
			
		$output_filename = dirname(__FILE__). DIRECTORY_SEPARATOR ."..". DIRECTORY_SEPARATOR ."plupload". DIRECTORY_SEPARATOR .$date. DIRECTORY_SEPARATOR .$filename;
		$output_path = "/plupload/".$date."/".$filename;
		
		$picname = $_FILES['mypic']['name'];
		$picsize = $_FILES['mypic']['size'];
		if ($picname != "") {
			if ($picsize > 204800) {
				echo '图片大小不能超过200k';
				exit;
			}
			$type = strstr($picname, '.');
			//if (strcasecmp($type,".GIF") != 0 && strcasecmp($type,".gif") != 0 && strcasecmp($type,".JPG") != 0 && strcasecmp($type,".jpg") != 0 && strcasecmp($type,".PNG") != 0 && strcasecmp($type,".png") != 0) {
			if(strcasecmp($type,".JPG") != 0 && strcasecmp($type,".jpg") != 0){
				echo '图片格式不对！';
				exit;
			}

			$pics = $output_path. $type;
			//上传路径
			$pic_path = $output_filename. $type;
			move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path);
			
			//上传完进行剪切
			//thumb($pic_path,320,568);
			//unlink($pic_path);
			$_SESSION['coverPath'] = $pics;
			$_SESSION['coverTruePath'] = $pic_path;
		}
		$size = round($picsize/1024,2);
		$arr = array(
			'name'=>$picname,
			'pic'=>$pics,
			'size'=>$size
		);
		echo json_encode($arr);
	}
?>