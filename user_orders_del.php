<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	$isw = 0; // 伪静态配置 1开启 0关闭
	date_default_timezone_set ( 'Asia/Shanghai' );
	include_once dirname ( __FILE__ ) . '/data/db/DB.php';
	include_once dirname ( __FILE__ ) . '/inc/page.class.php';
	include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
	$db = new DB();
	$uid = get_user_info();
	//-----删除订单-----//
	$id = $_GET['id']+0;
	$sql = "UPDATE f_order SET is_on=0 WHERE mid='$uid' AND id='$id'";
	if ($db->query($sql)) {
		echo "<script>alert('删除成功！');</script>";
		echo "<script>location.href='user_orders.php'</script>";
	}else{
		echo "<script>alert('删除失败！');</script>";
		echo "<script>location.href='user_orders.php'</script>";	
	}
	


?>