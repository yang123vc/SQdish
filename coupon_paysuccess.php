<?php

include_once dirname(__FILE__).'/data/db/DB.php';
require_once dirname(__FILE__).'/inc/alipay/alipay.config.php';
require_once dirname(__FILE__).'/inc/alipay/lib/alipay_notify.class.php';
$db = new DB();
    //¼ÆËãµÃ³öÍ¨ÖªÑéÖ¤½á¹û
    $alipayNotify = new AlipayNotify($alipay_config);
    $verify_result = $alipayNotify->verifyReturn();
    if($verify_result) {//ÑéÖ¤³É¹¦
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // ÇëÔÚÕâÀï¼ÓÉÏÉÌ»§µÄÒµÎñÂß¼­³ÌÐò´úÂë

        //¡ª¡ªÇë¸ù¾ÝÄúµÄÒµÎñÂß¼­À´±àÐ´³ÌÐò£¨ÒÔÏÂ´úÂë½ö×÷²Î¿¼£©¡ª¡ª
        //»ñÈ¡Ö§¸¶±¦µÄÍ¨Öª·µ»Ø²ÎÊý£¬¿É²Î¿¼¼¼ÊõÎÄµµÖÐÒ³ÃæÌø×ªÍ¬²½Í¨Öª²ÎÊýÁÐ±í

        //ÉÌ»§¶©µ¥ºÅ

        $out_trade_no = $_GET['out_trade_no'];

        //Ö§¸¶±¦½»Ò×ºÅ

        $trade_no = $_GET['trade_no'];

        //½»Ò××´Ì¬
        $trade_status = $_GET['trade_status'];

        $total_fee = $_GET['total_fee']; //½»Ò×½ð¶î

        //优惠券订单id
        $coupon_now = explode('coupon', $out_trade_no);
        $id = $coupon_now[0];
        $id_list = explode('_',$id);

        $orderInfo = $db->get_one("select * from f_coupon_tmp where id= '$id'");

        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
            //ÅÐ¶Ï¸Ã±Ê¶©µ¥ÊÇ·ñÔÚÉÌ»§ÍøÕ¾ÖÐÒÑ¾­×ö¹ý´¦Àí
            //Èç¹ûÃ»ÓÐ×ö¹ý´¦Àí£¬¸ù¾Ý¶©µ¥ºÅ£¨out_trade_no£©ÔÚÉÌ»§ÍøÕ¾µÄ¶©µ¥ÏµÍ³ÖÐ²éµ½¸Ã±Ê¶©µ¥µÄÏêÏ¸£¬²¢Ö´ÐÐÉÌ»§µÄÒµÎñ³ÌÐò
            //Èç¹ûÓÐ×ö¹ý´¦Àí£¬²»Ö´ÐÐÉÌ»§µÄÒµÎñ³ÌÐò
        }
        else {
            echo "trade_status=".$_GET['trade_status'];
        }
        foreach ($id_list as $key => $value) {
             $result_tmp = $db->query("update f_coupon_tmp set status = 1 where id = ".$value."");
        }
       

        /*$mid = $orderInfo['mid'];
        $cid = $orderInfo['cid'];
        $sid = $orderInfo['sid'];
        $couponcode = substr(md5($out_trade_no), 0, 16).$id;

        $result_mem = $db->query("insert into f_member_coupon(mid,cid,sid,couponcode,status) values('$mid','$cid','$sid','$couponcode',0)");*/

        $pay_record['order_id'] = $orderInfo['id'];
        $pay_record['out_trade_no'] =  $out_trade_no;
        $pay_record['trade_no'] = $trade_no;
        $pay_record['create_time'] = date('Y-m-d H:i:s',time());
        $pay_record['fee']  = $total_fee;
        $db->insert('f_pay_record',$pay_record);
        include_once dirname(__FILE__)."/templates/member/coupon_paysuccess.html";

        //¡ª¡ªÇë¸ù¾ÝÄúµÄÒµÎñÂß¼­À´±àÐ´³ÌÐò£¨ÒÔÉÏ´úÂë½ö×÷²Î¿¼£©¡ª¡ª

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    else {

        header("Content-type:text/html;charset=GBK");
        echo "error";
    }



?>


