<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/15
 * Time: 8:38
 */


//ini_set("display_errors",1);
        $isw=0;//Î±¾²Ì¬ÅäÖÃ  1¿ªÆô 0¹Ø±Õ
        date_default_timezone_set('Asia/Shanghai');
        include_once dirname(__FILE__).'/data/db/DB.php';
        include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
        $db = new DB();
        $sid = $_GET['shop_id'];

        $user_info = get_user_info();
        //$user_info = 1;
        $ret = array('login'=>0);
        //ÊÇ·ñµÇÂ½
        if(!empty($user_info)) {
            //ÊÇ·ñÔÚ±¾µê¹ºÂò¹ýÉÌÆ·
            $sql = "SELECT count(*) FROM f_order WHERE mid=$user_info AND sid = $sid AND status=1";
            $rs = $db->get_a($sql);
            if ($rs) {
                $ret = array('login'=>1);
            }else{
                $ret = array('login'=>2);
            }
        }else{
            $ret = array('login'=>0);
        }
       echo json_encode($ret);
