<?php
	//ini_set("display_errors",1);
	$isw=0;//伪静态配置  1开启 0关闭
	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	include_once dirname(__FILE__).'/inc/page.class.php';
	include_once dirname(__FILE__).'/inc/json_class.php';
	include_once dirname(__FILE__).'/inc/cookie_class.php';
	$db = new DB();
	$tid=intval($_GET['tid']);//栏目识别id
	//if(empty($tid)){
	//	echo "tid err!!";
	//	echo "<script>location.href='/'</script>";
	//	exit;
	//}
	//读取列表
	$pageSize = 60;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;
	//根据餐厅类型获取商家

	$rt_name = isset($_GET['rt_name'])?$_GET['rt_name']:'';

	//认证商户和在线支付;
	//人气最高和评论最好

	$act = isset($_GET['act'])?$_GET['act']:'';

	if ($rt_name=='') {
		if ($act=='is_rec') {
			//认证商户
			$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and pic<>'' and is_rec=1 order by id desc limit $location,$pageSize";
			$csql = "select count(*) as count from f_seller where is_on=1 and pic<>'' and is_rec=1";
		}else if ($act=='online_pay') {
			//在线支付
			$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and pic<>'' and online_pay=1 order by id desc limit $location,$pageSize";
			$csql = "select count(*) as count from f_seller where is_on=1 and pic<>'' and online_pay=1";
		}else if ($act=='click') {
			//点击量
			$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and pic<>'' order by click desc limit $location,$pageSize";
			$csql = "select count(*) as count from f_seller where is_on=1 and pic<>''";
		}else if($act=='comment'){
			//评价最多
			$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and pic<>'' order by id desc limit $location,$pageSize";
			$csql ="select count(*) as count from f_seller where is_on=1 and pic<>''";
		}else if($act=='closer'){
			//最近
			$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and pic<>'' order by id desc limit $location,$pageSize";
			$csql = "select count(*) as count from f_seller where is_on=1 and pic<>''";			
		}else{
			$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and pic<>'' order by id desc limit $location,$pageSize";
			$csql = "select count(*) as count from f_seller where is_on=1 and pic<>''";
		}
		$List = $db->get_all($sql);
		//根据商家获取每家extra
		$sellerList = array();
		if ($List) {
			foreach ($List as $v) {
				$sql = "select ser_name from f_dish_extra where sid='$v[id]' and is_on=1";
				$ser_name = $db->get_all($sql);	
				foreach ($ser_name as $k) {
					$sql = "select ser_icon from f_dish_extra where ser_name='$k[ser_name]' and sid=0 and is_on=1";
					$v['ser_icon'][] = $db->get_a($sql);
				}
				//点餐次数
				$sql = "select count(*) from f_statistics where sid='$v[id]'";
				$sum= $db->get_a($sql);
				$v['count'] =$sum*10;
				//评分

				//获取评论星级
				$sql = "select star from f_comment where shop_id='$v[id]'";
				$star_list = $db->get_all($sql);
				$sum = 0;
				foreach ($star_list as $key => $value) {
					$sum += $value['star'];
				}
				$count = count($star_list);
				$star_avg = ceil($sum/$count);
				//默认给4星
				if ($star_avg==0) {
					$star_avg = 4;
				}
				$v['star_avg'] = $star_avg;
				$sellerList[]=$v;   
			}
		}

		//分页
		$temp = $db->get_one($csql);
		$count = $temp['count'];
		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
	}else{
		$sql = "select sid from f_dish_rtype where rt_name='$rt_name' and is_on=1";
		$sidList = $db->get_all($sql);
		$List = array();
		foreach ($sidList as $v) {
			if ($act=='is_rec') {
				$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and is_rec=1 and id='$v[sid]'";
			}else if ($act=='online_pay') {
				$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and online_pay=1 and id='$v[sid]'";
			}else if($act=='click'){
				$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and id='$v[sid]'";
			}else if($act=='comment'){
				$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and id='$v[sid]'";
			}else if($act=='closer'){
				$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and id='$v[sid]'";
			}else{
				$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and id='$v[sid]'";
			}		
			$List[] = $db->get_one($sql);
		}
		$List = array_filter($List);
		$sellerList = array();
		foreach ($List as $v) {
			$sql = "select ser_name from f_dish_extra where sid='$v[id]' and is_on=1";
			$ser_name = $db->get_all($sql);	
			foreach ($ser_name as $k) {
				$sql = "select ser_icon from f_dish_extra where ser_name='$k[ser_name]' and sid=0 and is_on=1";
				$v['ser_icon'][] = $db->get_a($sql);
			}
			//点餐次数
			$sql = "select count(*) from f_statistics where sid='$v[id]'";
			$sum= $db->get_a($sql);
			$v['count'] =$sum*10;
			
				//获取评论星级
			
				$sql = "select star from f_comment where shop_id='$v[id]'";
				$star_list = $db->get_all($sql);
				$sum = 0;
				foreach ($star_list as $key => $value) {
					$sum += $value['star'];
				}
				$count = count($star_list);
				$star_avg = ceil($sum/$count);
				//默认给4星
				if ($star_avg==0) {
					$star_avg = 4;
				}
				$v['star_avg'] = $star_avg;	
				$sellerList[]=$v;   
		}
		//数组分页
		$sellerList =array_slice($sellerList,$location,$pageSize,true);
		$count= count($List);

		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
								'prev_label'=>'<<',
								'next_label'=>'>>',
								'last_label'=>'>>>',));
	}

	//获取自定义分类
	$sql = "select * from f_dish_rtype where sid=0 and is_on=1";
	$rtype = $db->get_all($sql);


	

		

							
							
							
	//读取所有栏目	
	$sql = "select * from f_article_category order by categoryRank asc limit 10";
	$CList = $db->get_all($sql);
	foreach($CList as $k=>$v){
		$newkey=$v['id'];
		$t[$newkey]=$v;
	}
	$CList=$t;
	//print_r($CList);
	
	//读取当前栏目信息
	if($tid){
			$sql = "select * from f_article_category where id = $tid ";		
		$cr  = $db->get_one($sql);//栏目信息
	}
	
	
	//读取当前栏目推荐信息
	if(empty($tid)){
		$where="where articleIst=1";
	}else{
		$where="where articleCategory=$tid  and articleIst=1";
	}
	
	$sqls = "select * from f_article $where order by id desc limit 10";
	$talists  = $db->get_all($sqls);//栏目推荐信息	
	//print_r($talists);
	
	
	//
	

$agent = $_SERVER['HTTP_USER_AGENT'];  

if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

	include_once dirname(__FILE__)."/templates/wap/business_list.html";

}else {	

	include_once dirname(__FILE__)."/templates/business_list.html";

};
	
	
	
?>