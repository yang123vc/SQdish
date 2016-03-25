<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/11
 * Time: 12:33
 */
ini_set("display_errors",1);
$isw=0;//Î±¾²Ì¬ÅäÖÃ  1¿ªÆô 0¹Ø±Õ
date_default_timezone_set('Asia/Shanghai');
include_once dirname(__FILE__).'/data/db/DB.php';

    $db = new DB();
    if(!session_id())
    session_start();
    if(!empty($_SESSION['user_info']))
    {
        $mid = $_SESSION['user_info']['id'];

    } else {
        $mid = 0;
    }
  // print_r($_SESSION['user_info']);exit;

    $type =  $_POST['type'];
    $shop_id = $_POST['shop_id'];
    $goods_id = $_POST['goods_id'];
    $article_id = $_POST['article_id'];
    $coupon_id = $_POST['coupon_id'];
    $fid = $shop_id;

    if( $type == 1 )
    {
        $fid = $shop_id;
    } else if( $type == 2 ) {
        $fid = $goods_id;
    } else if($type==3){
        $fid = $article_id;
    }else{
        $fid = $coupon_id;
    }


    $info  =  $db->get_one("select * from f_favourite where mid= " .$mid.  " and fid=".$fid);

    $num = 0 ;
    if(empty($info))
    {
        $num = 0;
    } else {
        $num = count($info);
    }

    $data['fid'] = $fid;
    $data['mid'] = $mid;
    $data['status'] = 0;
    $data['cat'] = $type;
    $result = 0;
    if($num==0){
       $result =  $db->insert('f_favourite',$data);
    } else {

       $result =  $db->update('f_favourite',$data,"id=".$info['id']);

    }
   echo json_encode($result);

