<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';
	$db = new DB();		if(isset($_REQUEST['oid']) && $_REQUEST['oid'])
		$id = $_REQUEST['oid'];	else		$id = -1;		$action = $_REQUEST['action'];
	if($action == "del"){
		$sql = "delete from f_dish_options_config where id = $id";
		$db->query($sql);
		echo "<script>location.href='OptionManage.php'</script>";
		exit;
	}
	if(isset($_REQUEST['option']))	
		$option = $_REQUEST['option'];
	else
		$option = '';
	if($action == "add"){
		//初始化
		$optionCN = "";
		$optionTHAI = "";
		$optionName = "";
		$optionName = $option['optionName'];		$optionCNArry = $option['optionCN'];
		foreach($optionCNArry as $k => $v){
			$optionCN .= $v.","; 
		}		
		if(strlen($optionCN) > 1)
			$optionCN = substr($optionCN,0,strlen($optionCN)-1); 		
		$optionTHAIArry = $option['optionTHAI'];
		foreach($optionTHAIArry as $k => $v){
			$optionTHAI .= $v.","; 
		}		
		if(strlen($optionTHAI) > 1)
			$optionTHAI = substr($optionTHAI,0,strlen($optionTHAI)-1); 				if($id == -1)
			$sql = "insert into f_dish_options_config(optionCN,optionTHAI,optionName) values('$optionCN','$optionTHAI','$optionName')";		else			$sql = "update f_dish_options_config set optionCN = '$optionCN',optionTHAI = '$optionTHAI',optionName = '$optionName' where id = $id";		
		$db->query($sql);
	}	
	echo "<script>location.href='OptionManage.php'</script>";
?>