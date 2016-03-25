<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/12
 * Time: 15:14
 */

    $isw=0;//伪静态配置  1开启 0关闭
    date_default_timezone_set('Asia/Shanghai');
    include_once dirname(__FILE__).'/data/db/DB.php';
    include_once dirname(__FILE__).'/inc/articleFunction.php';
    include_once dirname(__FILE__).'/inc/cart.php';
    include_once dirname(__FILE__).'/inc/json_class.php';
    include_once dirname(__FILE__).'/inc/cookie_class.php';
    require_once dirname(__FILE__).'/inc/alipay/alipay.config.php';
    require_once dirname(__FILE__).'/inc/alipay/lib/alipay_submit.class.php';

    require_once dirname(__FILE__).'/inc/wxpay/lib/WxPay.Api.php';
    require_once dirname(__FILE__).'/inc/wxpay/WxPay.NativePay.php';
    require_once dirname(__FILE__).'/inc/wxpay/log.php';
    $db = new DB();


    $orderid = $_GET['orderid'];
    $orderNo = $_GET['orderNo'];
    $orderinfo =  $db->get_one("select id,all_cost,pay_type,status from f_order where id = " .$orderid. " and ordercode='" .$orderNo. "'");

    if(empty($orderinfo))
    {
        $index_url = "http://".$_SERVER['HTTP_HOST'];
        header("Location: ".$index_url);
        exit;
    }

    $out_trade_no = $orderNo;
    $total_fee = 0.01;
    if( $orderinfo['pay_type'] == 1 )
    {

        $notify = new NativePay();
        $input = new WxPayUnifiedOrder();
        $input->SetBody( WxPayConfig::BODY );
        $input->SetAttach( WxPayConfig::ATTACH );
        $input->SetOut_trade_no( $orderNo );
        $input->SetTotal_fee( $total_fee*100 );
        $input->SetTime_start( date("YmdHis") );
        $input->SetTime_expire( date("YmdHis", time() + 600) );
        $input->SetGoods_tag( WxPayConfig::GOODS_TAG );
        $input->SetNotify_url( WxPayConfig::NOTIFY_URL);
        $input->SetTrade_type( WxPayConfig::TRADE_TYPE);
        $input->SetProduct_id("");
        $result = $notify->GetPayUrl($input);
        $url2 = $result["code_url"];

    }
    else
    {
        //构造要请求的参数数组，无需改动
        $parameter = array(
            "service" => "create_direct_pay_by_user",
            "partner" => trim($alipay_config['partner']),
            "seller_email" => trim($alipay_config['seller_email']),
            "payment_type"	=> $alipay_config['payment_type'],
            "notify_url"	=> $alipay_config['notify_url'],
            "return_url"	=> $alipay_config['return_url'],
            "out_trade_no"	=> $out_trade_no,
            "subject"	=> $alipay_config['subject'],
            "total_fee"	=> $total_fee,
            "body"	=> $alipay_config['body'],
            "show_url"	=> $alipay_config['show_url'],
            "anti_phishing_key"	=> $alipay_config['anti_phishing_key'],
            "exter_invoke_ip"	=> $alipay_config['exter_invoke_ip'],
            "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
        );

        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
    }
    include_once dirname(__FILE__)."/templates/member/ordersuccess.html";