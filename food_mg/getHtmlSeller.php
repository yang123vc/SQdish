<?php

	include_once dirname(__FILE__).'/../data/db/DB.php';

	include_once dirname(__FILE__).'/../inc/isLogin.config.php';

	include_once dirname(__FILE__).'/../util/phpqrcode/qrcodeGenerator.php';

	include_once dirname(__FILE__).'/../util/param.php';

	

	ini_set('max_execution_time', '6000');

	

	if(isset($_REQUEST['begin']))

		$begin = $_REQUEST['begin'];

	

	if(isset($_REQUEST['end']))

		$end = $_REQUEST['end'];

	

	$db = new DB();




	$where = "";

	

	if($begin && $end){

		$where = "where id >= $begin and id <= $end";

	}else if($begin){

		$where = "where id >= $begin";

	}else if($end){

		$where = "where id <= $end";

	}


	$sql = "select ser_name from f_dish_extra where sid='$id'";
	$ser_name = $db->get_all($sql);
		foreach ($ser_name as $k) {
			$sql = "select ser_icon from f_dish_extra where ser_name='$v[ser_name]'";
			$sellerIcon= $db->get_one($sql);
			var_dump($sellerIcon);exit;
		}
	

	$sql = "select * from f_seller $where";
	$sellerList = $db->get_all($sql);

	

	//echo "更新...<br>";

	

	foreach($sellerList as $k => $v){

		//echo $v['id']."   ";

		//echo $v['path']."<br>";

		//flush();

		

		//条件判断数据（基础依赖数据）

		$id = $v['id'];

		$path = $v['path'];

		//$FILE_PATH = "";

		//$WEB_PATH = "";

		//$time = date('His',time());

		//$date =  date('Ymd',time());

		//$filename = $time.".html";

		//一、数据库操作 准备阶段（附带处理删除）

		

		//数据准备
	
		$sellerName = $v['sellerName'];

		$sellerName = replace($sellerName, "'", "\'");

		$coordinates = $v['coordinates'];

		$address = $v['address'];

		$contacts = $v['contacts'];

		$tel = $v['tel'];

		$country = $v['country'];

		$intro = $v['intro'];

		$pic = $v['pic'];

		$statisticalCode = getValue('statisticalCode');

		$referee = $v['referee'];

		

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

		$content = replace($content, "{sellerName}", "00000000000");

		$content = replace($content, "{intro}", $intro);

		$content = replace($content, "{coordinates}", $coordinates);

		$content = replace($content, "{address}", $address);

		$content = replace($content, "{dishButton}", $dishButton);

		$content = replace($content, "{statisticalCode}", $statisticalCode);

		//$content = replace($content,"{sellerIcon}",$sellerIcon);
		//
		

		//3.判断新加还是修改，写入文件

		$FILE_PATH = dirname(__FILE__)."/..".$path;

		

		$myfile = fopen($FILE_PATH, "w");

		fwrite($myfile, $content);

		fclose($myfile);

		

		//4.通过文件网络路径生成二维码

		/*if($action=="add"){

			//增加

			$sql = "insert into f_seller(sellerName,coordinates,address,contacts,tel,intro,pic,path,referee,country) values('$sellerName','$coordinates','$address','$contacts','$tel','$intro','$pic','$path',$referee,'$country')";

			$db->query($sql);

			

			$id = $db->insert_id();

		}

		

		$preUrl = 'http://'.$_SERVER['SERVER_NAME'];

		$QR_PATH = "/QRCode/".$date."/";

		$QRCode = qrcodeGenerator($QR_PATH, $preUrl."/c/".$id, "H");

		*/

	}

	

	//echo "更新完成！";

	

	//三、数据库操作，存储数据

	

	/*if($action=="add"){

		$sql = "update f_seller set QRCode='$QRCode' where id='$id'";

		$db->query($sql);

	}else if($action=="update"){

		//修改

		$sql = "update f_seller set referee=$referee,sellerName='$sellerName',coordinates='$coordinates',address='$address',contacts='$contacts',tel='$tel',intro='$intro',pic='$pic',QRCode='$QRCode',path='$path',country='$country' where id='$id'";

		$db->query($sql);

	}*/

	

	echo "<script>location.href='sellerList.php'</script>";

?>