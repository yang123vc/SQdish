  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname ( __FILE__ ) . '/inc/page.class.php';
	include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
	$db = new DB();
	$user_info = get_user_info();
	//收藏相关操作


	$act = trim($_GET['act']);
	//删除收藏
	if ($act = 'delete') {
		//被收藏的id
		$id = $_GET['id']+0;
		$sql = "DELETE FROM f_favourite WHERE mid='$user_info' AND fid='$id'";
		$rs = $db->query($sql);
		if ($rs) {
			echo "<script>alert('删除成功！')</script>";		
		}else{
			echo "<script>alert('删除失败！')</script>";
		}
		echo "<script>location.href='favourite.php'</script>";
		exit;	
	}

?>