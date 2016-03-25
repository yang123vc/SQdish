<?php
	include_once dirname(__FILE__).'/data/db/DB.php';
	
	$db = new DB();

	$typeName = $_POST['typeName'];
	if($typeName=='hot'){
		$sql = "select * from f_seller where is_on=1 order by click desc limit 12";
		$hotList = $db->get_all($sql);
		echo json_encode($hotList);
	}else{
		$sql = "select sid from f_dish_rtype where rt_name='$typeName' and is_on=1 and sid<>0 order by rt_id desc limit 12";
		$sidList = $db->get_all($sql);
		$sellerList =array();
		foreach ($sidList as $v) {
			$sql="select * from f_seller where id='$v[sid]'";
			$sellerList[] = $db->get_one($sql);
		}
		echo json_encode($sellerList);
	}

	
	//echo $List;


?>