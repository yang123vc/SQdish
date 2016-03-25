<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/13
 * Time: 13:15
 */

include_once dirname(__FILE__).'/data/db/DB.php';
require_once dirname(__FILE__).'/inc/alipay/alipay.config.php';
require_once dirname(__FILE__).'/inc/alipay/lib/alipay_notify.class.php';
$db = new DB();
    //计算得出通知验证结果
    $alipayNotify = new AlipayNotify($alipay_config);
    $verify_result = $alipayNotify->verifyReturn();
    if($verify_result) {//验证成功
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //请在这里加上商户的业务逻辑程序代码

        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

        //商户订单号

        $out_trade_no = $_GET['out_trade_no'];

        //支付宝交易号

        $trade_no = $_GET['trade_no'];

        //交易状态
        $trade_status = $_GET['trade_status'];

        $total_fee = $_GET['total_fee']; //交易金额

        $orderInfo = $db->get_one("select *from f_order where ordercode= '".$out_trade_no."'");


        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序
        }
        else {
            echo "trade_status=".$_GET['trade_status'];
        }

        $result = $db->query("update f_order set status = 1 where id = ".$orderInfo['id']."");
        $db->query("update f_order_detail set dish_status = 1 where oid = ".$orderInfo['id']."");
        
        $pay_record['order_id'] = $orderInfo['id'];
        $pay_record['out_trade_no'] =  $out_trade_no;
        $pay_record['trade_no'] = $trade_no;
        $pay_record['create_time'] = date('Y-m-d H:i:s',time());
        $pay_record['fee']  = $total_fee;
        $db->insert('f_pay_record',$pay_record);
        include_once dirname(__FILE__)."/templates/member/paysuccess.html";

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    else {

        header("Content-type:text/html;charset=GBK");
        echo "验证失败";
    }



?>


