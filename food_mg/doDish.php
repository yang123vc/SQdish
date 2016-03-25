<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	
	//dish 依赖
	$db = new DB();

	$id = $_REQUEST['id'];
	$action = $_REQUEST['action'];
	
	if($action == "del"){
		$sql = "delete from f_dish where id = $id";
		$db->query($sql);
		
		$sql = "delete from f_dish_options where did = $id";
		$db->query($sql);
		
		echo "<script>location.href='dishList.php'</script>";
		exit;
	}
	
	$sid = $_REQUEST[SID];	if(!$sid)		exit;
	$dtid = $_REQUEST['dtid'];
	$dishNameCN = $_REQUEST['dishNameCN'];
	$dishNameTHAI = $_REQUEST['dishNameTHAI'];
	$dishIntroCN = $_REQUEST['dishIntroCN'];
	$dishIntroTHAI = $_REQUEST['dishIntroTHAI'];
	$pic = $_REQUEST['pic'];
	
	if(isset($_REQUEST['price']) && $_REQUEST['price'] != "")
		$price = $_REQUEST['price'];
	else
		$price = 0;
	
	if(isset($_REQUEST['recommend']) && $_REQUEST['recommend'] != "")
		$recommend = $_REQUEST['recommend'];
	else
		$recommend = 0;
		
	if(isset($_REQUEST['option']))	
		$option = $_REQUEST['option'];
	else
		$option = '';
	if($action == "add"){
		$sql = "insert into f_dish(sid,dtid,recommend,price,dishNameCN,dishNameTHAI,dishIntroCN,dishIntroTHAI,pic) ".
			"values($sid,$dtid,$recommend,$price,'$dishNameCN','$dishNameTHAI','$dishIntroCN','$dishIntroTHAI','$pic')";
		$db->query($sql);
		$did = $db->insert_id();				$sql = "select * from f_dictionaries where cn = '$dishNameCN'";		$fd = $db->get_one($sql);		if(!$fd){			$sql = "insert into f_dictionaries(cn,thai,pic) values('$dishNameCN','$dishNameTHAI','$pic')";			$db->query($sql);		}	
		foreach($option as $key => $value){
			//初始化
			$optionCN = "";
			$optionTHAI = "";
			$optionPrice = "";
			$optionName = "";
			
			$optionName = $value['optionName'];
			
			$optionCNArry = $value['optionCN'];
			foreach($optionCNArry as $k => $v){
				$optionCN .= $v.","; 
			}
			
			if(strlen($optionCN) > 1)
				$optionCN = substr($optionCN,0,strlen($optionCN)-1); 
			
			$optionTHAIArry = $value['optionTHAI'];
			foreach($optionTHAIArry as $k => $v){
				$optionTHAI .= $v.","; 
			}
			
			if(strlen($optionTHAI) > 1)
				$optionTHAI = substr($optionTHAI,0,strlen($optionTHAI)-1); 
			
			$optionPriceArry = $value['optionPrice'];
			foreach($optionPriceArry as $k => $v){
				$optionPrice .= $v.","; 
			}
			
			if(strlen($optionPrice) > 1)
				$optionPrice = substr($optionPrice,0,strlen($optionPrice)-1); 
			
			$sql = "insert into f_dish_options(did,optionCN,optionTHAI,optionPrice,optionName) values($did,'$optionCN','$optionTHAI','$optionPrice','$optionName')";
			$db->query($sql);
		}
	}else if($action == "update"){
		$sql = "update f_dish set dtid = $dtid,recommend = $recommend,price = $price,dishNameCN = '$dishNameCN',dishNameTHAI = '$dishNameTHAI',dishIntroCN = '$dishIntroCN',dishIntroTHAI = '$dishIntroTHAI',pic = '$pic'".
		" where id = $id";
		$db->query($sql);		$sql = "select * from f_dictionaries where cn = '$dishNameCN'";		$fd = $db->get_one($sql);		if(!$fd){			$sql = "insert into f_dictionaries(cn,thai,pic) values('$dishNameCN','$dishNameTHAI','$pic')";			$db->query($sql);		}
		foreach($option as $key => $value){
			//初始化
			$oid = $key;
			$optionCN = "";
			$optionTHAI = "";
			$optionPrice = "";
			$optionName = "";
			
			$optionName = $value['optionName'];
			
			$optionCNArry = $value['optionCN'];
			foreach($optionCNArry as $k => $v){
				$optionCN .= $v.","; 
			}
			
			if(strlen($optionCN) > 1)
				$optionCN = substr($optionCN,0,strlen($optionCN)-1); 
			
			$optionTHAIArry = $value['optionTHAI'];
			foreach($optionTHAIArry as $k => $v){
				$optionTHAI .= $v.","; 
			}
			
			if(strlen($optionTHAI) > 1)
				$optionTHAI = substr($optionTHAI,0,strlen($optionTHAI)-1); 
			
			$optionPriceArry = $value['optionPrice'];
			foreach($optionPriceArry as $k => $v){
				$optionPrice .= $v.","; 
			}
			
			if(strlen($optionPrice) > 1)
				$optionPrice = substr($optionPrice,0,strlen($optionPrice)-1); 			
			
			if(is_numeric($oid))
				$op = $db->get_one("select * from f_dish_options where id = $oid");
			else
				$op = false;
			
			if($op)
				$sql = "update f_dish_options set optionCN = '$optionCN',optionTHAI = '$optionTHAI',optionPrice = '$optionPrice',optionName = '$optionName' where id = $oid";
			else
				$sql = "insert into f_dish_options(did,optionCN,optionTHAI,optionPrice,optionName) values($id,'$optionCN','$optionTHAI','$optionPrice','$optionName')";
			
			$db->query($sql);
		}
	}
	
	echo "<script>location.href='dishList.php'</script>";
?>