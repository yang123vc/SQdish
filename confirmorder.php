<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/11
 * Time: 20:06
 */
    
    ini_set("display_errors",1);
    $isw=0;//Î±¾²Ì¬ÅäÖÃ  1¿ªÆô 0¹Ø±Õ
    date_default_timezone_set('Asia/Shanghai');
    include_once dirname(__FILE__).'/data/db/DB.php';
    include_once dirname(__FILE__).'/inc/cart.php';
    include_once dirname(__FILE__).'/inc/json_class.php';
    include_once dirname(__FILE__).'/inc/cookie_class.php';
	
	$db = new DB();

   if(!session_id())
    session_start();

	//--------------ÅÐ¶ÏÊÇ·ñµÇÂ¼-------------------//
          if(!isset($_SESSION['user_info']) )
          {
              $jump_url = 'http://' . $_SERVER['HTTP_HOST'] . '/login.php';
              header("Location:" . $jump_url);
              exit;
          }



	//--------------ÅÐ¶ÏÊÇ·ñµÇÂ¼-------------------//

    $Cart = new Cart();
    $carinfo = $Cart->getMyCart();
    $selectedGoods = $_POST['selectedGoods'];
    $unselectedGoods = $_POST['unselectedGoods'];
    $sourceFlag = isset($_POST['sourceFlag'])?$_POST['sourceFlag']:0;
    //
    $imGoodsId = isset( $_POST['goods_id'] )?$_POST['goods_id']: 0;
    $imGoodsImg = isset($_POST['goods_img'])?$_POST['goods_img']:'';
    $imGoodsName = isset($_POST['goods_name'])?$_POST['goods_name']:'';
    $imGoodsPrice = isset($_POST['goods_price'])?$_POST['goods_price']:0;
    $imShopId = isset($_POST['shop_id'])?$_POST['shop_id']:0;
    $imGoodsNum = isset($_POST['imBuyNum'])?$_POST['imBuyNum']:0;

    //



    $goods_list = array();
    if( $sourceFlag == 2 )
    {
        $goods_list[$imGoodsId]['id'] = $imGoodsId;
        $goods_list[$imGoodsId]['count'] = $imGoodsNum;
        $goods_list[$imGoodsId]['img'] = $imGoodsImg;
        $goods_list[$imGoodsId]['name'] = $imGoodsName;
        $goods_list[$imGoodsId]['shop_id'] = $imShopId;
        $goods_list[$imGoodsId]['cost'] = $imGoodsPrice;

    }
    else
    {
        foreach( $carinfo['list'] as $shop_key=>$shop)
        {
            foreach( $shop['data'] as $goods_id=>$goods)
            {
                $goods_list[$goods_id]['id'] = $goods['id'];
                $goods_list[$goods_id]['count'] = $goods['count'];
                $goods_list[$goods_id]['img'] = $goods['img'];
                $goods_list[$goods_id]['name'] = $goods['name'];
                $goods_list[$goods_id]['shop_id'] = $shop_key;
                $goods_list[$goods_id]['cost'] = $goods['cost'];
            }

        }

    }


    if( $sourceFlag==1) {
        if ($unselectedGoods) {
            $unselectedGoods = trim($unselectedGoods, ',');
            $unselectedArray = explode(',', $unselectedGoods);
            $length = count($unselectedArray);
            for ($i = 0; $i < $length; $i++) {
                $arr = explode('_', $unselectedArray[$i]);
                $shop_id = $arr[1];
                $goods_id = $arr[0];
                $num = $arr[2];
                unset($goods_list[$goods_id]);
                $Cart->del($goods_id,$shop_id);
            }

        }
    }




    $selectedSum = 0;
    $selectedNum = 0;
    foreach($goods_list as $v)
    {
        $selectedSum += $v['cost']*$v['count'];
        $selectedNum += $v['count'];
    }
    $exchangeRateArray = $db->get_one("select * from f_param where id = 1");

    //$RmbSum = round(trim($exchangeRateArray['value']) * $selectedSum,2);
    $RmbSum = sprintf("%.2f", round(trim($exchangeRateArray['value']) * $selectedSum,2));



    $url = "http://".$_SERVER['HTTP_HOST'];


    include_once dirname(__FILE__)."/templates/confirmorder.html";
