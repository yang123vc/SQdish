<?php
/**移动端评论页
 * Created By Sublime.
 * Author:Don fisher
 * Date: 2016/2/19
 */
    ini_set("display_errors",1);
    $isw=0;//伪静态配置  1开启 0关闭
    date_default_timezone_set('Asia/Shanghai');
    include_once dirname(__FILE__).'/data/db/DB.php';
  $db = new DB();


    //店铺id
    $sid = $_GET['sid']+0;
    if (empty($sid)) {
    	echo "<script>location.href='user_index.php'</script>";
    	exit;
    }else{  
    	$sql = "select sellerName,pic from f_seller where id='$sid'";
    	$seller_info = $db->get_one($sql);
    }
    include_once dirname(__FILE__)."/templates/wap/member/ToEvaluate.html";



?>