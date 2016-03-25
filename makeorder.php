<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/12
 * Time: 13:34
 */
    ini_set("display_errors",1);
    $isw=0;//伪静态配置  1开启 0关闭
    date_default_timezone_set('Asia/Shanghai');
    include_once dirname(__FILE__).'/data/db/DB.php';
    include_once dirname(__FILE__).'/inc/articleFunction.php';
    include_once dirname(__FILE__).'/inc/cart.php';
    include_once dirname(__FILE__).'/inc/json_class.php';
    include_once dirname(__FILE__).'/inc/cookie_class.php';


    if(!session_id())
    session_start();
    $db = new DB();
    $pay_type = $_POST['pay_type'];
    $confirm_flag = isset($_POST['confirm_flag'])?$_POST['confirm_flag']:0;
    $carinfo = JSON::decode(str_replace(array('&','$'),array('"',','),ICookie::get('shoppingcart')));
    $Cart = new Cart();
    $carinfo = $Cart->getMyCart();

    $index_url = "http://".$_SERVER['HTTP_HOST'];
    if(($confirm_flag!=1))
    {
        header("Location: ".$index_url);
        exit;
    }




    $im_buy_flag = isset($_POST['im_buy_flag'])?$_POST['im_buy_flag']:0;
    $imGoodsId = isset( $_POST['goods_id'] )?$_POST['goods_id']: 0;
    $imGoodsImg = isset($_POST['goods_img'])?$_POST['goods_img']:'';
    $imGoodsName = isset($_POST['goods_name'])?$_POST['goods_name']:'';
    $imGoodsPrice = isset($_POST['goods_price'])?$_POST['goods_price']:0;
    $imShopId = isset($_POST['shop_id'])?$_POST['shop_id']:0;
    $imGoodsNum = isset($_POST['imBuyNum'])?$_POST['imBuyNum']:0;

    //print_r($im_buy_flag);exit;

    $goods_list = array();

    $goods_count = 0;
    $goods_sum = 0;
    if($im_buy_flag == 1)
    {
        $goods_list[$imGoodsId]['id'] = $imGoodsId;
        $goods_list[$imGoodsId]['count'] = $imGoodsNum;
        $goods_list[$imGoodsId]['img'] = $imGoodsImg;
        $goods_list[$imGoodsId]['name'] = $imGoodsName;
        $goods_list[$imGoodsId]['shop_id'] = $imShopId;
        $goods_list[$imGoodsId]['cost'] = $imGoodsPrice;
        $goods_count = $imGoodsNum;
        $goods_sum = $imGoodsPrice * $imGoodsNum;
       // print_r($goods_sum);exit;

    }
    else
    {
        foreach( $carinfo['list'] as $shop_key=>$shop)
        {
            foreach( $shop['data'] as $goods_id=>$goods)
            {
                $goods_list[$goods_id]['id'] = $goods['id'];
                $goods_list[$goods_id]['img'] = $goods['img'];
                $goods_list[$goods_id]['cost'] = $goods['cost'];
                $goods_list[$goods_id]['count'] = $goods['count'];
                $goods_list[$goods_id]['shop_id'] = $shop_key;
            }

        }

    }


    $mid = isset($_SESSION['user_info']['id'])?$_SESSION['user_info']['id']:0;


    $orderArray['mid'] = $mid;
    $orderArray['status'] = 0;
    $orderArray['ordercode'] = time().rand(1000,9999);
    $orderArray['pay_type'] = $pay_type;
    $orderArray['all_cost'] = ($im_buy_flag == 0)?$carinfo['sum']:$goods_sum;
    $orderArray['count']  = ($im_buy_flag == 0)?$carinfo['count']:$goods_count;
    $orderArray['time'] = time();
    $orderArray['sid'] = ($im_buy_flag == 0)?$goods_list[$goods_id]['shop_id']:$imShopId; 
  
    $orderid = 0;
    mysql_query('START TRANSACTION');
    $order_result = $db->insert('f_order',$orderArray);
    if(!$order_result)
    {
        mysql_query('ROLLBACK');

    }
    $order_id = $db->insert_id();
    $order_detail_result = false;
    $update_goods_result = false;
    foreach($goods_list as $details)
    {
        $orderDetailsArray['oid'] = $order_id;
        $orderDetailsArray['did'] = $details['id'];
        $orderDetailsArray['goods_price'] = $details['cost'];
        $orderDetailsArray['goods_count'] = $details['count'];
        $order_detail_result = $db->insert('f_order_detail',$orderDetailsArray);
        $update_goods_result = $db->query("update f_dish set used = used + ".$details['count']." where id = ".$details['id']."");

    }
    if( $update_goods_result == false )
    {
        mysql_query('ROLLBACK');
    }
    if( $order_detail_result==false )
    {
        mysql_query('ROLLBACK');
    }

    mysql_query('COMMIT');
    $Cart->clear();

    //$order_success_url  = "http://".$_SERVER['HTTP_HOST']."/ordersuccess.php?orderid=".$order_id."&orderNo=".$orderArray['ordercode'];
    $order_success_url  = "http://".$_SERVER['HTTP_HOST']."/ordersuccess.php";
   echo "<form style='display:none;' id='form' name='form' method='post' action='{$order_success_url}'>
              <input name='orderid' type='text' value='{$order_id}' />
              <input name='orderNo' type='text' value='{$orderArray["ordercode"]}'/>
            </form>
            <script type='text/javascript'>function load_submit(){document.form.submit()}load_submit();</script>";







