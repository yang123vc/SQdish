<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../util/phpqrcode/qrcodeGenerator.php';
	include_once dirname(__FILE__).'/../util/param.php';
	//print_r($_POST);exit;
	$db = new DB();
		/*$new_extra = array();
		foreach ($_POST as $k=>$v) {
			if(is_numeric($k)){
				$new_extra[$k] = $v;
			}	
		}
		print_r($new_extra);exit;*/
	//条件判断数据（基础依赖数据）
	$action = $_REQUEST['action'];
	$page = $_REQUEST['page'];
	$id = $_REQUEST['id']+0;
	$path = $_POST['path'];
	$FILE_PATH = "";
	$WEB_PATH = "";
	$time = date('His',time());
	$date =  date('Ymd',time());
	$filename = $time.".html";
	//图标
	if($action=='add'){
		$icon_list=array();
		foreach ($_POST as $k=>$v) {
		if(is_numeric($k)){
			$sql = "select ser_icon from f_dish_extra where ser_name='$v'and sid=0 and is_on=1";
			$icon_list[]=$db->get_one($sql);	
			}	
		}

	}
	if($action=='update') {
		$icon_list = array();
		foreach ($_POST as $k=>$v) {
			if(is_numeric($k)){
			$sql = "select ser_icon from f_dish_extra where ser_name='$v' and sid=0 and is_on=1";
			$icon_list[]= $db->get_one($sql);	
			}	
		}
	}
	//print_r($icon_list);exit;

	
	//一、数据库操作 准备阶段（附带处理删除）
	//删除
	if($action == "del"){
		$sql = "delete from f_seller where id = $id";
		$db->query($sql);
		$sql = "delete from f_dish_extra where sid ='$id'";
		$db->query($sql);
		$sql = "delete from f_dish_rtype where sid ='$id'";
		$db->query($sql);
		echo "<script>location.href='sellerList.php'</script>";
		exit;
	}
	//echo $id;exit;


	//print_r($icon_list);exit;
	
	//数据准备
	$sellerName = $_POST['sellerName'];
	if (empty($sellerName)) {
		echo "<script>alert('请填写商家名')</script>";
		echo "<script>location.href='sellerAdd.php'</script>";
		exit;
	}
	$sellerName = replace($sellerName, "'", "\'");
	$sellerNameCN = replace(trim($_POST['sellerNameCN']),"'", "\'");
	$sellerNameTHAI = replace(trim($_POST['sellerNameTHAI']),"'", "\'");
	$coordinates = $_POST['coordinates'];
	if (empty($coordinates)) {
		echo "<script>alert('请填写坐标')</script>";
		echo "<script>location.href='sellerAdd.php'</script>";
		exit;
	}
	$address = $_POST['address'];
	$contacts = $_POST['contacts'];
	if (empty($contacts)) {
		echo "<script>alert('请填写联系人')</script>";
		echo "<script>location.href='sellerAdd.php'</script>";
		exit;
	}
	$tel = $_POST['tel'];
	if (empty($tel)) {
		echo "<script>alert('请填写电话')</script>";
		echo "<script>location.href='sellerAdd.php'</script>";
		exit;
	}
	$BH = $_POST['BH'];//营业时间
	if (empty($BH)) {
		echo "<script>alert('请填写营业时间')</script>";
		echo "<script>location.href='sellerAdd.php'</script>";
		exit;
	}
	$mer_address = trim($_POST['mer_address']);
	$is_rec = $_POST['is_rec']+0;//是否推荐
	$online_pay = $_POST['online_pay']+0;//线上支付
	$QRCode_sum = $_POST['QRcode_sum']+0;//所需二维码数量
	$is_on = $_POST['is_on']+0;//审核状态
	$reg_time = time();//添加时间
	$type = isset($_POST['checkbox'])?$_POST['checkbox']:array();
	//print_r($type);exit;
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
		$extra = $db->get_all("select ser_icon from f_dish_extra where sid='$id'");

	
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
	//echo $id;exit;
	
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
	$icon =array();
	foreach ($icon_list as $v) {
		$icon[] = "<img style='width:30px;height:30px;'src='$v[ser_icon]' alt='' />";	
	}
	$icon = implode(" ", $icon);
	//echo $icon;exit;
	$content = replace($content, "{sellerIcon}",$icon);
	
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
		$sql = "insert into f_seller(sellerName,sellerNameCN,sellerNameTHAI,coordinates,address,contacts,tel,intro,pic,path,referee,country,state,city,region,BH,QRCode_sum,is_on,reg_time,is_rec,online_pay,mer_address) values('$sellerName','$sellerNameCN','$sellerNameTHAI','$coordinates','$address','$contacts','$tel','$intro','$pic','$path',$referee,'$country','$state','$city','$region','$BH','$QRCode_sum','$is_on','$reg_time','$is_rec','$online_pay','$mer_address')";
		$db->query($sql);
		$id = $db->insert_id();	
		//新增图标
		//初始化图标
		$sql = "select ser_id,ser_name from f_dish_extra where is_on=1 and sid=0";
		$extra = $db->get_all($sql);
		foreach ($extra as $v) {
			$sql = "insert into f_dish_extra(sid,ser_name,is_on) values('$id','$v[ser_name]',0)";
			$db->query($sql);
		}
		foreach ($_POST as $k=>$v) {
			if(is_numeric($k)){
				$sql = "update f_dish_extra set is_on=1 where sid='$id' and ser_name='$v'";
				//$sql = "insert into f_dish_extra(sid,ser_name,is_on) values('$id','$v',1)";
				$db->query($sql);	
			}	
		}
		//添加餐厅类型
		//初始化类型
		$sql = "select rt_id,rt_name from f_dish_rtype where is_on=1 and sid=0";
		$rtype =  $db->get_all($sql);
		foreach ($rtype as $v) {
			$sql = "insert into f_dish_rtype(sid,rt_name,is_on) values('$id','$v[rt_name]',0)";
			$db->query($sql);
		}
		foreach ($type as $v) {
			$sql = "update f_dish_rtype set is_on=1 where sid='$id' and rt_name='$v'";
			//$sql = "insert into f_dish_rtype(sid,rt_name,is_on) values('$id','$v',1)";
			$db->query($sql);
		}
		
		
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
		$sql = "update f_seller set referee=$referee,sellerName='$sellerName',sellerNameCN='$sellerNameCN',sellerNameTHAI='$sellerNameTHAI',coordinates='$coordinates',address='$address',contacts='$contacts',tel='$tel',intro='$intro',pic='$pic',QRCode='$QRCode',path='$path',country='$country',state='$state',city='$city',region='$region',BH='$BH',QRCode_sum='$QRCode_sum',is_on='$is_on',reg_time='$reg_time',is_rec='$is_rec',online_pay='$online_pay',mer_address='$mer_address' where id='$id'";
		$db->query($sql);			
		//修改额外服务	
		$new_extra = array();
		foreach ($_POST as $k=>$v) {
			if(is_numeric($k)){
				$new_extra[$k] = $v;
			}	
		}

			$sql = "delete from f_dish_extra where sid='$id'";
			$db->query($sql);
			$sql = "select ser_id,ser_name from f_dish_extra where is_on=1 and sid=0";
			$extra =  $db->get_all($sql);
			foreach ($extra as $v) {
				$sql = "insert into f_dish_extra(sid,ser_name,is_on) values('$id','$v[ser_name]',0)";
				$db->query($sql);
			}

			foreach ($new_extra as $v) {
				$sql = "update f_dish_extra set is_on=1 where ser_name='$v' and sid='$id'";
				$db->query($sql);
			}

		//编辑类型
			//删除旧的类型
			$sql = "delete from f_dish_rtype where sid='$id'";
			$db->query($sql);
			//获得现有类型
			$sql = "select rt_id,rt_name from f_dish_rtype where is_on=1 and sid=0";
			$rtype =  $db->get_all($sql);
			//写入全部类型，状态默认为关闭0
			foreach ($rtype as $v) {
				$sql = "insert into f_dish_rtype(sid,rt_name,is_on) values('$id','$v[rt_name]',0)";
				$db->query($sql);
			}
			//根据表单数据更改状态开启1
			foreach ($type as $v) {
				$sql = "update f_dish_rtype set is_on=1 where rt_name='$v' and sid='$id'";
				$db->query($sql);
			}

		

		/*if(empty($old_extra)){
			foreach ($new_extra as $value) {
				$sql = "insert into f_dish_extra(sid,ser_name,is_on) values('$id','$value',1)";
				$db->query($sql);
			}
		}else{
			//有数据则更改状态
			//初始化
			$sql = "update f_dish_extra set is_on=0 where sid='$id'";
			$db->query($sql);
			if(empty($new_extra)){
					$sql = "update f_dish_extra set is_on=0 where sid ='$id'";
					$db->query($sql);
			}else if(count($new_extra)>count($old_extra)){
				$old = array();
				foreach ($old_extra as $v) {
					$old[] = $v['ser_name'];
				}
				$new = array_diff($new_extra,$old);	
				foreach ($new as $v) {
						$sql = "insert into f_dish_extra(sid,ser_name,is_on) values('$id','$v',1)";
						$db->query($sql);
					}
				$sql = "update f_dish_extra set is_on=1 where sid='$id'";	
				$db->query($sql);		
			}else if(count($new_extra)==count($old_extra)){
				$sql = "update f_dish_extra set is_on=1 where sid='$id'";	
				$db->query($sql);
			}else{
			foreach ($old_extra as $v) {				 	
					foreach ($new_extra as $value) {
						if(in_array($value, $v)){	
						      $sql = "update f_dish_extra set is_on=1 where ser_name='$value' and sid='$id'";
						      $db->query($sql);	
						   
						}    	
					}
				}
			}
		}*/
		//编辑类型


		/*if(empty($old_type)){
			foreach ($type as $value) {
				$sql = "insert into f_dish_rtype(sid,rt_name,is_on) values('$id','$value',1)";
				$db->query($sql);
			}
		}else{
			//有数据则更改状态
			//初始化
			$sql = "update f_dish_rtype set is_on=0 where sid='$id'";
			$db->query($sql);
			if(empty($type)){
					$sql = "update f_dish_rtype set is_on=0 where sid ='$id'";
					$db->query($sql);
			}else if(count($type)>count($old_type)){
				$old = array();
				foreach ($old_type as $v) {
					$old[] = $v['rt_name'];
				}
				$new = array_diff($type,$old);	
				foreach ($new as $v) {
						$sql = "insert into f_dish_rtype(sid,rt_name,is_on) values('$id','$v',1)";
						$db->query($sql);
					}
				$sql = "update f_dish_rtype set is_on=1 where sid='$id'";	
				$db->query($sql);		
			}else if(count($type)==count($old_type)){
				$sql = "update f_dish_rtype set is_on=1 where sid='$id'";	
				$db->query($sql);
			}else{
			foreach ($old_type as $v) {				 	
					foreach ($type as $value) {
						if(in_array($value, $v)){	
						      $sql = "update f_dish_rtype set is_on=1 where rt_name='$value' and sid='$id'";
						      $db->query($sql);	
						   
						}    	
					}
				}
			}
		}*/
		

	}
	if ($_SESSION[USERSESSION]['role']==3) {
		if ($action=='add') {
		echo "<script>alert('商家信息上传成功，请根据商家id:".$id."完善负责人信息')</script>";
		echo "<script>location.href='UserAdd.php?sid=$id'</script>";
		}else if($action=='update'){
			echo "<script>alert('商家信息修改成功，请完善负责人信息')</script>";	
			echo "<script>location.href='sellerUpdate.php?id=$id&sid=$id'</script>";
		}
		
	}else{
		echo "<script>location.href='sellerList.php?page=$page'</script>";
	}
	
?>