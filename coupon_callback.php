<?php

include_once dirname(__FILE__).'/data/db/DB.php';
require_once dirname(__FILE__).'/inc/alipay/alipay.config.php';
require_once dirname(__FILE__).'/inc/alipay/lib/alipay_notify.class.php';
$db = new DB();

    $alipayNotify = new AlipayNotify($alipay_config);
    $verify_result = $alipayNotify->verifyReturn();
    if($verify_result) {

        $out_trade_no = $_GET['out_trade_no'];

        $trade_no = $_GET['trade_no'];

        $trade_status = $_GET['trade_status'];

        $total_fee = $_GET['total_fee'];

        $orderInfo = $db->get_one("select * from f_order where ordercode= '".$out_trade_no."'");

        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
            //支付成功
        }
        else {
            echo "trade_status=".$_GET['trade_status'];
        }

        $result = $db->query("update f_order set status = 1 where id = ".$orderInfo['id']."");

        $pay_record['order_id'] = $orderInfo['id'];
        $pay_record['out_trade_no'] =  $out_trade_no;
        $pay_record['trade_no'] = $trade_no;
        $pay_record['create_time'] = date('Y-m-d H:i:s',time());
        $pay_record['fee']  = $total_fee;
        $db->insert('f_pay_record',$pay_record);
        include_once dirname(__FILE__)."/templates/member/paysuccess.html";
    }
    else {

        header("Content-type:text/html;charset=utf-8");
        echo "ÑéÖ¤Ê§°Ü";
    }



?>


