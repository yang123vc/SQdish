<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../util/phpqrcode/qrcodeGenerator.php';
	include_once dirname(__FILE__).'/../util/param.php';
	
	$db = new DB();
	
	//条件判断数据（基础依赖数据）
	$action = $_REQUEST['action'];
	$id = $_REQUEST['id'];
	$path = $_POST['path'];
	$FILE_PATH = "";
	$WEB_PATH = "";
	$time = date('His',time());
	$date =  date('Ymd',time());
	$filename = $time.".html";
	
	//一、数据库操作 准备阶段（附带处理删除）
	//删除
	if($action == "del"){
		$sql = "delete from f_seller where id = $id";
		$db->query($sql);
		
		echo "<script>location.href='ArticleList.php'</script>";
		exit;
	}
	
	//数据准备
	$sellerName = $_POST['sellerName'];
	$sellerName = replace($sellerName, "'", "\'");
	$coordinates = $_POST['coordinates'];
	$address = $_POST['address'];
	$contacts = $_POST['contacts'];
	$tel = $_POST['tel'];
	
	/*$country = $_POST['country'];	
	$state = $_POST['state'];	
	$city = $_POST['city'];	
	$region = $_POST['region'];*/
	
	$country = "";	
	$state = "";	
	$city = "";	
	$region = "";
	
	$intro = $_POST['intro'];
	$pic = $_POST['pic'];
	$statisticalCode = getValue('statisticalCode');
	
	if(isset($_POST['referee']) && $_POST['referee'])
		$referee = $_POST['referee'];
	else 
		$referee = 0;
	
	if($id)
		$dish = $db->get_all("select * from f_dish where sid = $id");
	
	if(isset($dish) && $dish)
		$dishButton = '<ul class="am-avg-sm-2 boxes">
				<li><a class="am-btn am-btn-secondary am-btn-block" href=" https://www.google.com/maps/place/'.$coordinates.'"><i class="am-icon-map-marker"></i> 导航</a></li>
				<li><a class="am-btn am-btn-secondary am-btn-block" href="/dish/?id='.$id.'"><i class="am-icon-cutlery"></i> 菜单</a></li>
			</ul>';
	else	
		$dishButton = '<ul class="am-avg-sm-1 boxes">
				<li><a class="am-btn am-btn-secondary am-btn-block" href=" https://www.google.com/maps/place/'.$coordinates.'"><i class="am-icon-map-marker"></i> 导航</a></li>
			</ul>';
	
	$QRCode;
	
	
	//二、文件操作
	//生成静态页面
	//1.读取模板
	//可放入constant中 直接调用 绝对路径
	$TEMPLATE_PATH = dirname(__FILE__)."/../html/landing.html"; 
	$content = file_get_contents($TEMPLATE_PATH); //读取文件中的内容
	
	//2.翻译内容
	$content = replace($content, "{pic}", $pic);
	$content = replace($content, "{sellerName}", $sellerName);
	$content = replace($content, "{intro}", $intro);
	$content = replace($content, "{address}", $address);
	$content = replace($content, "{coordinates}", $coordinates);
	$content = replace($content, "{address}", $address);
	$content = replace($content, "{dishButton}", $dishButton);
	$content = replace($content, "{statisticalCode}", $statisticalCode);
	
	//3.判断新加还是修改，写入文件
	if($action=="add"){
		//创建目录
		$PATH = dirname(__FILE__). "/../file/".$date;
		if(!is_dir($PATH))
			mkdir($PATH);
		
		//物理路径
		$FILE_PATH = $PATH."/".$filename;
		//网络路径
		$path = "/file/".$date."/".$filename;
	}else if($action=="update"){
		//拼接物理路径
		$FILE_PATH = dirname(__FILE__)."/..".$path;
	}
	
	$myfile = fopen($FILE_PATH, "w");
	fwrite($myfile, $content);
	fclose($myfile);
	
	//4.通过文件网络路径生成二维码
	if($action=="add"){
		//增加
		$sql = "insert into f_seller(sellerName,coordinates,address,contacts,tel,intro,pic,path,referee,country,state,city,region) values('$sellerName','$coordinates','$address','$contacts','$tel','$intro','$pic','$path',$referee,'$country','$state','$city','$region')";
		$db->query($sql);
		
		$id = $db->insert_id();
	}
	
	$preUrl = 'http://'.$_SERVER['SERVER_NAME'];
	$QR_PATH = "/QRCode/".$date."/";
	$QRCode = qrcodeGenerator($QR_PATH, $preUrl."/c/".$id, "H");
	
	//三、数据库操作，存储数据
	
	if($action=="add"){
		$sql = "update f_seller set QRCode='$QRCode' where id='$id'";
		$db->query($sql);
	}else if($action=="update"){
		//修改
		$sql = "update f_seller set referee=$referee,sellerName='$sellerName',coordinates='$coordinates',address='$address',contacts='$contacts',tel='$tel',intro='$intro',pic='$pic',QRCode='$QRCode',path='$path',country='$country',state='$state',city='$city',region='$region' where id='$id'";
		$db->query($sql);
	}
	
	echo "<script>location.href='ArticleList.php'</script>";
?>