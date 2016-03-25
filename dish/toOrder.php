<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />  
<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	include_once dirname(__FILE__).'/../util/param.php';
	include_once dirname(__FILE__).'/../util/param.php';
	session_start();
	
	$db = new DB();
	
	
	$did = $_REQUEST['did'];
	$id = $_REQUEST['id'];
	if (empty($id)) {
		$id = $db->get_a("select sid from f_dish where id = '$did[0]'");
	}
	$nameCh = $_REQUEST['nameCh'];
	$nameThai = $_REQUEST['nameThai'];
	$price = $_REQUEST['price'];
	$opName = $_REQUEST['opName'];
	$opNameThai = $_REQUEST['opNameThai'];
	$num = $_REQUEST['num'];
	$exchangeRate = getValue('exchangeRate');
	
	$statisticalCode = getValue('statisticalCode');
	
	$date = date('y-m-d H:i',time());
	$detail = "";
	$totalPrice = 0.00;
	
	for($i=0;$i<count($did);$i++){
		if($opName[$i]){
			$opN = "--$opName[$i]($opNameThai[$i])";
		}else {
			$opN = "";
		}
		
		$detail .= "$nameCh[$i]($nameThai[$i])$opN<br>";
		$totalPrice += $price[$i];
		
		$oNum = $num[$i];
		$oDid = $did[$i];
		$sql = "update f_dish set used = used+$oNum where id = $did[$i]";
		$db->query($sql);
	}
	
	$sql = "insert into f_statistics(`date`,detail,price,sid) values('$date','$detail',$totalPrice,$id)";
	$db->query($sql);
	
	if(isset($_SESSION["cart"])){
		unset($_SESSION["cart"]);
	}
	
	include_once dirname(__FILE__)."/success.html";		
?>