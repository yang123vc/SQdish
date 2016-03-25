<?php

ini_set("display_errors",1);
$isw=0;//Î±¾²Ì¬ÅäÖÃ  1¿ªÆô 0¹Ø±Õ
date_default_timezone_set('Asia/Shanghai');
include_once dirname(__FILE__).'/data/db/DB.php';
require_once dirname(__FILE__).'/inc/alipay/alipay.config.php';
require_once dirname(__FILE__).'/inc/alipay/lib/alipay_notify.class.php';
$db = new DB();

//¼ÆËãµÃ³öÍ¨ÖªÑéÖ¤½á¹û
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//ÑéÖ¤³É¹¦
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //ÇëÔÚÕâÀï¼ÓÉÏÉÌ»§µÄÒµÎñÂß¼­³ÌÐò´ú


    //¡ª¡ªÇë¸ù¾ÝÄúµÄÒµÎñÂß¼­À´±àÐ´³ÌÐò£¨ÒÔÏÂ´úÂë½ö×÷²Î¿¼£©¡ª¡ª

    //»ñÈ¡Ö§¸¶±¦µÄÍ¨Öª·µ»Ø²ÎÊý£¬¿É²Î¿¼¼¼ÊõÎÄµµÖÐ·þÎñÆ÷Òì²½Í¨Öª²ÎÊýÁÐ±í

    //ÉÌ»§¶©µ¥ºÅ

    $out_trade_no = $_POST['out_trade_no'];

    //Ö§¸¶±¦½»Ò×ºÅ

    $trade_no = $_POST['trade_no'];

    //½»Ò××´Ì¬
    $trade_status = $_POST['trade_status'];

    $total_fee = $_POST['total_fee']; //½»Ò×½ð¶î

     //优惠券订单id
    $coupon_now = explode('coupon', $out_trade_no);
    $id = $coupon_now[0];


    $orderInfo = $db->get_one("select * from f_coupon_tmp where id= '$id'");



    if($_POST['trade_status'] == 'TRADE_FINISHED') {
        //ÅÐ¶Ï¸Ã±Ê¶©µ¥ÊÇ·ñÔÚÉÌ»§ÍøÕ¾ÖÐÒÑ¾­×ö¹ý´¦Àí
        //Èç¹ûÃ»ÓÐ×ö¹ý´¦Àí£¬¸ù¾Ý¶©µ¥ºÅ£¨out_trade_no£©ÔÚÉÌ»§ÍøÕ¾µÄ¶©µ¥ÏµÍ³ÖÐ²éµ½¸Ã±Ê¶©µ¥µÄÏêÏ¸£¬²¢Ö´ÐÐÉÌ»§µÄÒµÎñ³ÌÐò
        //ÇëÎñ±ØÅÐ¶ÏÇëÇóÊ±µÄtotal_fee¡¢seller_idÓëÍ¨ÖªÊ±»ñÈ¡µÄtotal_fee¡¢seller_idÎªÒ»ÖÂµÄ
        //Èç¹ûÓÐ×ö¹ý´¦Àí£¬²»Ö´ÐÐÉÌ»§µÄÒµÎñ³ÌÐò

        //×¢Òâ£º
        //ÍË¿îÈÕÆÚ³¬¹ý¿ÉÍË¿îÆÚÏÞºó£¨ÈçÈý¸öÔÂ¿ÉÍË¿î£©£¬Ö§¸¶±¦ÏµÍ³·¢ËÍ¸Ã½»Ò××´Ì¬Í¨Öª

        //µ÷ÊÔÓÃ£¬Ð´ÎÄ±¾º¯Êý¼ÇÂ¼³ÌÐòÔËÐÐÇé¿öÊÇ·ñÕý³£
        //logResult("ÕâÀïÐ´ÈëÏëÒªµ÷ÊÔµÄ´úÂë±äÁ¿Öµ£¬»òÆäËûÔËÐÐµÄ½á¹û¼ÇÂ¼");
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
        //ÅÐ¶Ï¸Ã±Ê¶©µ¥ÊÇ·ñÔÚÉÌ»§ÍøÕ¾ÖÐÒÑ¾­×ö¹ý´¦Àí
        //Èç¹ûÃ»ÓÐ×ö¹ý´¦Àí£¬¸ù¾Ý¶©µ¥ºÅ£¨out_trade_no£©ÔÚÉÌ»§ÍøÕ¾µÄ¶©µ¥ÏµÍ³ÖÐ²éµ½¸Ã±Ê¶©µ¥µÄÏêÏ¸£¬²¢Ö´ÐÐÉÌ»§µÄÒµÎñ³ÌÐò
        //ÇëÎñ±ØÅÐ¶ÏÇëÇóÊ±µÄtotal_fee¡¢seller_idÓëÍ¨ÖªÊ±»ñÈ¡µÄtotal_fee¡¢seller_idÎªÒ»ÖÂµÄ
        //Èç¹ûÓÐ×ö¹ý´¦Àí£¬²»Ö´ÐÐÉÌ»§µÄÒµÎñ³ÌÐò

        //×¢Òâ£º
        //¸¶¿îÍê³Éºó£¬Ö§¸¶±¦ÏµÍ³·¢ËÍ¸Ã½»Ò××´Ì¬Í¨Öª

        //µ÷ÊÔÓÃ£¬Ð´ÎÄ±¾º¯Êý¼ÇÂ¼³ÌÐòÔËÐÐÇé¿öÊÇ·ñÕý³£
        //logResult("ÕâÀïÐ´ÈëÏëÒªµ÷ÊÔµÄ´úÂë±äÁ¿Öµ£¬»òÆäËûÔËÐÐµÄ½á¹û¼ÇÂ¼");
    }

    //¡ª¡ªÇë¸ù¾ÝÄúµÄÒµÎñÂß¼­À´±àÐ´³ÌÐò£¨ÒÔÉÏ´úÂë½ö×÷²Î¿¼£©¡ª¡ª
    $result = $db->query("update f_coupon_tmp set status = 1 where id = ".$orderInfo['id']."");

    $mid = $orderInfo['mid'];
    $c_tmp_id = $orderInfo['id'];
    $cid = $orderInfo['cid'];
    $sid = $orderInfo['sid'];
    $couponcode = substr(md5($out_trade_no), 0, 16).$id;
    $result_mem = $db->query("insert into f_member_coupon(mid,c_tmp_id,cid,sid,couponcode,status) values('$mid','$c_tmp_id','$cid','$sid','$couponcode',0)");

    $pay_record['order_id'] = $orderInfo['id'];
    $pay_record['out_trade_no'] =  $out_trade_no;
    $pay_record['trade_no'] = $trade_no;
    $pay_record['create_time'] = date('Y-m-d H:i:s',time());
    $pay_record['fee']  = $total_fee;
    $db->insert('f_pay_record',$pay_record);

    echo "success";		//Çë²»ÒªÐÞ¸Ä»òÉ¾³ý

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //ÑéÖ¤Ê§°Ü
    echo "fail";

    //µ÷ÊÔÓÃ£¬Ð´ÎÄ±¾º¯Êý¼ÇÂ¼³ÌÐòÔËÐÐÇé¿öÊÇ·ñÕý³£
    //logResult("ÕâÀïÐ´ÈëÏëÒªµ÷ÊÔµÄ´úÂë±äÁ¿Öµ£¬»òÆäËûÔËÐÐµÄ½á¹û¼ÇÂ¼");
}