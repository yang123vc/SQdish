<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/15
 * Time: 10:28
 */

    //ini_set("display_errors",1);
    $isw=0;//Î±¾²Ì¬ÅäÖÃ  1¿ªÆô 0¹Ø±Õ
    date_default_timezone_set('Asia/Shanghai');
    include_once dirname(__FILE__).'/data/db/DB.php';
    include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
    $db = new DB();

    $user_info = get_user_info();


//    if(empty($user_info))
//    {
//        echo json_encode(array(
//            'ok' => 0,
//            'error' => '±ØÐëÏÈµÇÂ¼',
//        ));
//       // exit;
//    }
//       echo json_encode(array(
//            'ok' => 0,
//             'error' => '±ØÐëÏÈµÇÂ¼',
//        ));
    $uploaddir = "./file/".date('Ymd',time()).'/';
    $savedir = "/file/".date('Ymd',time()).'/';
    $type=array("jpg","gif","bmp","jpeg","png");
    $data['content'] = trim($_POST['content']);
    $data['star'] = $_POST['star'];
    $data['shop_id'] = $_POST['shop_id'];
    $data['addtime'] = time();
    $data['member_id'] = $user_info;

    $arr=array();
    $count=0;
    if(!file_exists($uploaddir)){
        mkdir($uploaddir);
    }

    foreach ($_FILES["pic"]["name"] as $key => $name)
    {
        $tmp_name = $_FILES["pic"]["tmp_name"][$key];
        $name    = $_FILES["pic"]["name"][$key];
        $uploadfile = $uploaddir.$name;
        move_uploaded_file($tmp_name, $uploadfile);
        if(!empty($name))
        {
            $arr[$count]=$savedir.$name;
            $count++;

        }

    }
    if(count($arr)>0)
    {
        $data['pic_num'] = count($arr);
    }
    $pic = implode(',',$arr);
    $data['pic'] = $pic;
    if(!empty($data['content']))
    {
        $res = $db->insert('f_comment', $data);
    }

    $comment_url = "http://".$_SERVER['HTTP_HOST'].'/business_comment.php?sid='.$data['shop_id'];
    header("Location: ".$comment_url);
    exit;

