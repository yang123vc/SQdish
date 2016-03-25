<?php
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	include_once dirname(__FILE__).'/../data/db/DB.php';
	$action = "add";
	
	

	if($_SESSION[USERSESSION]['role']==3){
		//获取推荐过的餐厅
		$referee = $_SESSION[USERSESSION]['id'];//推荐人id
		$db = new DB();
		$sql = "select id,sellerName from f_seller where referee='$referee'";
		$list = $db->get_all($sql);
		//获取推荐过的，但是还未添加管理员的商家
		/*foreach ($list as $v) {
			$sql = "select count(*) from f_user where sid='$v[id]'";
			$rs = $db->get_a($sql);
			if ($rs) {
				unset($v);
			}
			$sellerNameList[] = $v;
		}*/
		//print_r($sellerNameList);exit();

		//print_r($list);exit;
		include_once dirname(__FILE__)."/templates/refereeUserAdd.html";
	}else{
		include_once dirname(__FILE__)."/templates/UserAdd.html";
	}
?>