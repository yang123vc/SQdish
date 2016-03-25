<?php 
	include_once dirname(__FILE__).'/data/db/DB.php';

	$db = new DB();

	$rt_name = isset($_GET['rt_name'])?$_GET['rt_name']:'';
	$pageSize = 60;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;

	if ($rt_name=='') {
	$sql = "select * from f_seller where is_on=1 and pic<>'' order by id desc limit $location,$pageSize";
	$List = $db->get_all($sql);
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
	}else{
		$sql = "select id,sellerName,pic,BH,click from f_seller where is_on=1 and pic<>'' order by id desc limit $location,$pageSize";
		$csql = "select count(*) as count from f_seller where is_on=1 and pic<>''";
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
	}
	echo json_encode($sellerList);
?>
