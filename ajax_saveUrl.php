<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/15
 * Time: 9:41
 */

    //ini_set("display_errors",1);
    $isw=0;//α��̬����  1���� 0�ر�
    date_default_timezone_set('Asia/Shanghai');
    //include_once dirname(__FILE__).'/data/db/DB.php';
    //include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
    //$db = new DB();
    if(!session_id())
    session_start();
    $_SESSION['returnUrl'] = $_SERVER['HTTP_REFERER'];


