<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.dish.php';

	$rt_id = $_GET['rt_id']+0;
	//根据ser_id和sid删除服务
	$db = new DB();
	$sid = $_SESSION[SID];
	
	$del = $db->delete('f_dish_rtype','rt_id='.$rt_id.'');
	if ($del) {
		echo <<<eof
		<script type="text/javascript">
			alert('删除成功!');
			window.location.href='restaurantType.php';
		</script>
eof;
	} else {
		echo <<<eof
		<script type="text/javascript">
			alert('删除失败!');
			window.location.href='restaurantType.php';
		</script>
eof;
	}
	



?>