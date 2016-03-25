 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
	#Author:Don Fisher
	#Date:2016/2/15
	//----------搜索(商家，菜品，文章)----------//


	date_default_timezone_set('Asia/Shanghai');
	include_once dirname(__FILE__).'/data/db/DB.php';
	include_once dirname(__FILE__).'/inc/articleFunction.php';
	include_once dirname(__FILE__).'/inc/page.class.php';
	$db = new DB();


	$key = trim($_REQUEST['key']);
	$act = isset($_REQUEST['act'])?$_REQUEST['act']:1;

		//分页参数
	$pageSize = 60;//每页多少个
	if(isset($_GET['page']) && $_GET['page'] != "")
		$page = $_GET['page'];
	else
	$page = 1;
	$location = ($page-1)*$pageSize;





	/*根据分类act搜索
	1：搜索商家（商家名、商家类别）默认搜索
	2：搜索菜品（菜品名、菜品类别）
	3：搜索文章（关键字、标题）
	*/

	//判断你客户端
	$agent = $_SERVER['HTTP_USER_AGENT'];  

	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

	    	$is_mobil = true;

	}else {    

	 	$is_mobil = false;
	} 






	if ($act==1) {
		//栏目信息
		//获取自定义分类
		$sql = "select * from f_dish_rtype where sid=0 and is_on=1";
		$rtype = $db->get_all($sql);

		$catList = array();
		//获取全部商家类别
		$sql = "SELECT rt_name FROM f_dish_rtype WHERE sid=0 AND is_on=1";
		$cat_list = $db->get_all($sql);
		for ($i=0; $i <count($cat_list) ; $i++) { 
			$pos = strpos($cat_list[$i]['rt_name'],$key);
			if ($pos !== false) {
				$catList[$i] = $cat_list[$i]['rt_name'];
			}
		}
		//匹配到栏目则显示栏目下商家
		if (!empty($catList)) {
			foreach ($catList as $value) {
				$sql = "select sid from f_dish_rtype where rt_name='$value' and is_on=1";
				$sidList = $db->get_all($sql);
				$List = array();
				foreach ($sidList as $v) {
					$sql = "select * from f_seller where is_on=1 and id='$v[sid]'";		
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
			if ($is_mobil) {
				include_once dirname(__FILE__)."/templates/wap/business_search_list.html";
			}else{
				include_once dirname(__FILE__)."/templates/search_list.html";
			}
		}else{
			//匹配不到栏目则搜索商家名
		$sql = "select * from f_seller where is_on=1 and pic<>'' and sellerName like '%$key%' or sellerNameCN like '%$key%' or sellerNameTHAI like '%$key%' or mer_address like '%$key%'  order by id desc limit $location,$pageSize";
		$csql = "select count(*) as count from f_seller where is_on=1 and pic<>'' and sellerName like '%$key%' or sellerNameCN like '%$key%' or sellerNameTHAI like '%$key%' or mer_address like '%$key%'";
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
			if ($is_mobil) {
				include_once dirname(__FILE__)."/templates/wap/business_search_list.html";
			}else{
				include_once dirname(__FILE__)."/templates/search_list.html";
			}
		}
	}else if ($act==2) {
		//获取菜品分类
		$sql = "select distinct id,typeNameCN from f_dish_type order by rand() limit 30";
		$typeList = $db->get_all($sql);


		$sql = "select id,sid,dtid,pic,dishNameCN,price from f_dish where dishNameCN like '%$key%' or dishNameTHAI like '%$key%' or dishIntroCN like '%$key%'  or dishIntroTHAI like '%$key%' order by id desc limit $location,$pageSize";
		$dishList = $db->get_all($sql);
		//搜索为空提示
		$sql = "select count(*) from f_dish where dishNameCN like '%$key%' or dishNameTHAI like '%$key%' or dishIntroCN like '%$key%'  or dishIntroTHAI like '%$key%'";
		$rs=$db->get_a($sql);
		//分页参数

		$count = intval($rs);
		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
							'prev_label'=>'<<',
							'next_label'=>'>>',
							'last_label'=>'>>>',));
		
			if ($is_mobil) {
				include_once dirname(__FILE__)."/templates/wap/dish_search_list.html";
			}else{
				include_once dirname(__FILE__)."/templates/search_dish_list.html";
			}
	}else if($act==3){
		//分页参数
		$pageSize = 9;
		//读取文章栏目
		$sql = "select * from f_article_category order by categoryRank asc limit 10";
		$CList = $db->get_all($sql);
		foreach($CList as $k=>$v){
			$newkey=$v['id'];
			$t[$newkey]=$v;
		}
		$CList=$t;
		//读取推荐信息
		$sqls = "select * from f_article where articleIst=1 order by id desc limit 10";
		$talists  = $db->get_all($sqls);//栏目推荐信息

		//搜索文章
		$sql = "select * from f_article where articleTitle like '%$key%' or articleAuthor like '%$key%' or articleKeyword like '%$key%' or articleDes like '%$key%' order by id desc limit $location,$pageSize";
		$List = $db->get_all($sql);

		$sql = "select count(*) as count from f_article where articleTitle like '%$key%' or articleAuthor like '%$key%' or articleKeyword like '%$key%' or articleDes like '%$key%'";
		$temp = $db->get_one($sql);
		$count = $temp['count'];	
		$pages = new page($pageSize,$count,$isw); 
		$pages->set( $ary = array( 'display_str_flag'=>false,
								'prev_label'=>'<<',
								'next_label'=>'>>',
								'last_label'=>'>>>',));
		
		
			if ($is_mobil) {
				include_once dirname(__FILE__)."/templates/wap/article_search_lists.html";
			}else{
				include_once dirname(__FILE__)."/templates/search_article_list.html";
			}
	}
?>