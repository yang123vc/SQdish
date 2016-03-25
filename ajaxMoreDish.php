<?php
	include_once dirname(__FILE__).'/data/db/DB.php';
	$sid = $_POST['id']+0;
	//随机获取四道菜
	$db = new DB();
	$sql = "select * from f_dish where sid='$sid' order by rand() limit 4";
	$dishRand = $db->get_all($sql);
	echo json_encode($dishRand);


?>