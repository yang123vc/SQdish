<?php
/**移动端评论页
 * Created By Sublime.
 * Author:Don fisher
 * Date: 2016/2/19
 */
    ini_set("display_errors",1);
    $isw=0;//伪静态配置  1开启 0关闭
    date_default_timezone_set('Asia/Shanghai');
    include_once dirname(__FILE__).'/data/db/DB.php';
     include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
  $db = new DB();
 $user_info = get_user_info();

    $uploaddir = "./file/".date('Ymd',time()).'/';
    $savedir = "/file/".date('Ymd',time()).'/';
    $type=array("jpg","gif","bmp","jpeg","png");
    $data['content'] = trim($_POST['content']);
    $data['star'] = $_POST['star'];
    $sid = $data['shop_id'] = $_POST['shop_id'];
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
        if ($res) {
            echo "<script>location.href='comment.php'</script>";
        }else{
            echo "<script>alert('评论失败');</script>";
            echo "<script>location.href='toEvaluate.php?sid=$sid'</script>";
        }
    }


?>