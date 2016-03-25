<?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	include_once dirname(__FILE__).'/../util/param.php';
	session_start();

	$db = new DB();
	
	$pageSize = 10;
	
	if(isset($_REQUEST['dtid'])){
		$dtid = $_REQUEST['dtid'];
		$_SESSION["dtid"] = $dtid;
		
		//如果有分类，推荐赋值0 理论上分类推荐不同时存在
		$_SESSION["recommend"] = 0;
	}else{
		$dtid = 0;
		$_SESSION["dtid"] = 0;
	}
	
	if(isset($_REQUEST['recommend'])){
		$recommend = $_REQUEST['recommend'];
		$_SESSION["recommend"] = $recommend;
		
		//如果有推荐，分类赋值0
		$_SESSION["dtid"] = 0;
	}else{
		$recommend = 0;
		$_SESSION["recommend"] = 0;
	}
	
	$where = "";
	
	if($dtid != 0){
		$where .= " and dtid = $dtid";
	}
	
	if($recommend != 0)
		$where .= " and recommend = 1";
	
	$sid = $_REQUEST['id'];
	
	$seller = $db->get_one("select * from f_seller where id = $sid");
	$dish = $db->get_all("select * from f_dish where sid = $sid $where limit 0,$pageSize");
	$dishType = $db->get_all("select * from f_dish_type where sid = $sid");
	$statisticalCode = getValue('statisticalCode');
	
	//购物车
	$cart = "";

	if(isset($_SESSION['cart']))
		$cart = $_SESSION['cart'];
	else
		$_SESSION['cart'] = $cart;
		
	$result = getTotalResult($cart);

	foreach($dish as $k => $v){
		$id = $v['id'];
	
		if($v['recommend'] == 1)
			$rClass = "li-recommend";
		else
			$rClass = "";
			
		if($v['pic'] != ""){
			$divPic = '<!--为保证图集功能，有图片时，这个div层必须有-->'.
				'<div class="am-u-sm-4 am-list-thumb ">'.
					'<img src="'.$v['pic'].'" alt="'.$v['dishNameCN'].$v['dishNameTHAI'].'" />'.
				'</div>';
			$amNo = 8;
		}else{
			$divPic = '';
			$amNo = 12;
		}
		
		//图片 介绍
		echo  '<li class="am-g am-list-item-desced am-list-item-thumbed am-list-item-thumb-left '.$rClass.' typeId-'.$v['dtid'].'">'.
			$divPic.
			'<div class="am-u-sm-'.$amNo.' am-list-main">'.
			  '<h3 class="am-list-item-hd">'.
				'<a href="item.php?id='.$id.'&sid='.$sid.'">'.
				$v['dishNameCN'].'<br>'.$v['dishNameTHAI'].''.
				'</a>'.
			  '</h3>';
			  
		if($v['dishIntroCN'] || $v['dishIntroTHAI']) echo '<div class="am-list-item-text">'.$v['dishIntroCN'].'<br>'.$v['dishIntroTHAI'].'</div>';
			  
		echo '</div>'.
			'<!--属性和价格是分别用<div class="am-u-sm-12 margin_clear">包裹的，不能融合成一个-->'.
			'<div class="am-u-sm-12 margin_clear">';
			
		//属性
		$sql = "select * from f_dish_options where did = $id";
		$options = $db->get_all($sql);
		
		$info = "";
		$infoThai = "";
		$oPrice = 0;
		$opId = "";
		
		foreach($options as $k1 => $v1){
			$optionCN = $v1['optionCN'];
			$optionTHAI = $v1['optionTHAI'];
			$optionPrice = $v1['optionPrice'];
			
			$oCNs = explode(",",$optionCN);
			$oTHAIs = explode(",",$optionTHAI);
			$oPrices = explode(",",$optionPrice);

			$info .= $oCNs[0]." ";
			$infoThai .= $oTHAIs[0]." ";
			$opId .= "0";
			$oPrice += (float)$oPrices[0];
		}
		
		//默认
		
		echo '<div class="am-u-sm-12 margin_clear booking_price_margin">';
			if($info) echo '<span class="am-list-item-text">默认：'.$info.' （更多选择请点击菜品名）</span>';
		echo '</div>';

		
		//价钱 数量	
		$price = (float)$v['price']+ (float)$oPrice;
		$num = 0;
		
		if($cart){
			foreach($cart as $k1 => $v1){
				foreach($v1 as $k2 => $v2){
					$did = substr($k2,strpos($k2,"_")+1);
					
					if($did == $v['id']){
						$num += $v2["num"];
					}
				}
			}
		}
		
		echo '</div>'.
		'<div class="am-u-sm-12 margin_clear">'.
			  '<div class="am-u-sm-6 booking_price margin_clear">'.
				 '฿<span class="price-'.$v['id'].'">'. $price .'</span>铢'.
			  '</div>'.
			  '<div class="am-u-sm-6 booking_choice margin_clear">'.
				'<button type="button" onclick="toCart('.$v['dtid'].','.$v['id'].',\''.$v['dishNameCN'].'\',\''.$v['dishNameTHAI'].'\','.$price.',\''.$info.'\',\''.$infoThai.'\',\'minus\',\''.$opId.'\')" class="am-badge am-badge-secondary am-btn am-radius">-</button> '.
				'<span class="tPrice-'.$v['id'].'">'.$num.'</span> '.
				'<button type="button" onclick="toCart('.$v['dtid'].','.$v['id'].',\''.$v['dishNameCN'].'\',\''.$v['dishNameTHAI'].'\','.$price.',\''.$info.'\',\''.$infoThai.'\',\'add\',\''.$opId.'\')" class="am-badge am-badge-secondary am-btn am-radius">+</button>'.
			  '</div>'.
		'</div>'.
	  '</li>';
	}
	
	//if(count($dish) <= $pageSize){
		//echo "<li class = 'h2'>没有菜品了!</li><li>&emsp;</li>";
	//}
?>