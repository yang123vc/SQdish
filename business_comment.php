<?php
//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
	include_once dirname(__FILE__).'/inc/page.class.php';
	$db = new DB();

	if ($_SESSION['user_info']) {
		$mid = $_SESSION['user_info']['id'];
	}else{
		$mid = 1;
	}

	$pageSize = 20;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
		$page = 1;
	$location = ($page-1)*$pageSize;

   	 $pic_flag = isset($_GET['pic_flag'])?$_GET['pic_flag']:0;
	


		$sid = $shop_id =intval($_GET['sid']);
		//--------------判断是否登录-------------------//
		$user_info = get_user_info();
		//--------------判断是否登录-------------------//
        		$ret = array('login'=>0);


		$url = "http://".$_SERVER['HTTP_HOST'];

    	$wherePic = $pic==0?'':' and pic_num > 0';

    	//全部评价和有图评价
    	$act = $_GET['act'];
    	if ($act=='pic') {
    		$sql = "select a.*,b.username,b.nickname,thumbail,pic162,pic48,pic20 from f_comment as a left join f_member as b on a.member_id=b.id where a.shop_id = $shop_id  and a.pic_num<>0	 order by id  desc limit $location,$pageSize";
    	}else{
		$sql = "select a.*,b.username,b.nickname,thumbail,pic162,pic48,pic20 from f_comment as a left join f_member as b on a.member_id=b.id where a.shop_id = $shop_id  $wherePic	 order by id  desc limit $location,$pageSize";
	}
    $comments = $db->get_all($sql);
    foreach( $comments as $k=>$v)
	{

		if($v['pic']=='')
		{
			$comments[$k]['img_count'] = 0;
		}else {
			$arr = explode(',',$v['pic']);
			$comments[$k]['img'] =  $arr;
			$comments[$k]['img_count'] =  	count($arr);

		}



	}
	//根据sid获取商家详细信息
	$sql = "select * from f_seller where id='$sid'";
	$detail = $db->get_one($sql);
	//点餐次数
	$sql = "select count(*) from f_statistics where sid='$sid'";
	$sum= $db->get_a($sql);
	$detail['count'] =$sum*10;
	//清除文本格式
	function cutstr_html($string,$sublen){
	          $string = strip_tags($string);
	          $string = preg_replace ('/\n/is', '', $string);
	          $string = preg_replace ('/ |　/is', '', $string);
	          $string = preg_replace ('/&nbsp;/is', '', $string);
	          preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);   
	          if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
	          else $string = join('', array_slice($t_string[0], 0, $sublen));
	          return $string;
   	 }
	$detail['intro'] = cutstr_html($detail['intro'],300);



	//根据sid 获取图标
	$sql ="select ser_name from f_dish_extra where sid='$sid' and is_on=1";
	$ser_name = $db->get_all($sql);
	$ser_icon =array();
	foreach ($ser_name as $v) {
		$sql = "select ser_icon from f_dish_extra where ser_name='$v[ser_name]' and sid=0 and is_on=1";
		$ser_icon[]= $db->get_a($sql);
	}
    $count_array = $db->get_all("select count(*) as count from f_comment where shop_id=$shop_id $wherePic ");
    $count = $count_array[0]['count'];

   $total_count_array = $db->get_all("select count(*) as count from f_comment where shop_id=$shop_id ");
   $total_count = $total_count_array[0]['count'];

   $have_pic_count_array = $db->get_all("select count(*) as count from f_comment where shop_id=$shop_id  and pic_num>0");
   $have_pic_count = $have_pic_count_array[0]['count'];
	$pages = new page($pageSize,$count,$isw);
	$pages->set( $ary = array( 'display_str_flag'=>false,
		'prev_label'=>'<<',
		'next_label'=>'>>',
		'last_label'=>'>>>',));
	//print_r($comments);exit;

	 //收藏商户
	$fav_shop = $db->get_one("select * from f_favourite where status=0 and mid= " .$mid.  " and fid=".$sid);
	
	 $fav_flag_shop = 0;
	   if(empty($fav_shop))
	   {
		   $fav_flag_shop = 0;
	   } else {
		   $fav_flag_shop = 1;
	   }

	if ($act=='pic') {
		include_once dirname(__FILE__)."/templates/business_pic_comment.html";
	}else{
		include_once dirname(__FILE__)."/templates/business_comment.html";
	}
?>