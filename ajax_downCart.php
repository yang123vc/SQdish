<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/10
 * Time: 9:51
 */
ini_set("display_errors",1);
$isw=0;//Î±¾²Ì¬ÅäÖÃ  1¿ªÆô 0¹Ø±Õ
date_default_timezone_set('Asia/Shanghai');
include_once dirname(__FILE__).'/data/db/DB.php';
include_once dirname(__FILE__).'/inc/cart.php';
include_once dirname(__FILE__).'/inc/json_class.php';
include_once dirname(__FILE__).'/inc/cookie_class.php';

    $shop_id = intval($_POST['shop_id']);
    $goods_id = intval($_POST['goods_id']);
    $num = intval($_POST['num']);

    $Cart = new Cart();
    $carinfo = $Cart->getMyCart();
    $result = array();
    if(!isset($carinfo['list'][$shop_id]['data'][$goods_id]['count']))
    {
         $result = array('ok'=>0,'msg'=>'¹ºÎï³µÎª¿Õ');

          echo json_encode($result);
          exit;
    }
    if($carinfo['list'][$shop_id]['data'][$goods_id]['count'] ==  $num)
    {
        $checkinfo = $Cart->del($goods_id,$shop_id);
        if($checkinfo)
        {
            $result = array('ok'=>1,'msg'=>'success');
        }



    }else if($carinfo['list'][$shop_id]['data'][$goods_id]['count']>$num){
        $num = $num*(-1);
        if($carinfo['list'][$shop_id]['data'][$goods_id]['count']!=0)
        {
            $checkinfo = $Cart->add($goods_id,$num,$shop_id);
            if($checkinfo)
            {
                $result = array('ok'=>1,'msg'=>'success');
            }
        }

    } else {
        $checkinfo = $Cart->del($goods_id,$shop_id);
        if($checkinfo)
        {
            $result = array('ok'=>1,'msg'=>'success');
        }

    }

  echo json_encode($result);
  exit;
