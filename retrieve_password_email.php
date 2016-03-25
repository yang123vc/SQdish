<?php

$isw = 0; //伪静态配置  1开启 0关闭
date_default_timezone_set('Asia/Shanghai');
$email = isset($_GET['email']) ? $_GET['email'] : '';
$tocken = isset($_GET['token']) ? $_GET['token'] : '';
include dirname(__FILE__) . '/data/db/DB.php';
if (!empty($email) && !empty($tocken)) {
    $db = new DB();
    $sql = "select * from f_email_tocken where email = '$email' and tocken = '$tocken' ";
    $result = $db->get_one($sql);
    if (!empty($result)) {
        $opt = 1;
        //验证成功，使验证信息过期
        $db->delete('f_email_tocken', "email = '$email' and tocken = '$tocken' ");
        include_once dirname(__FILE__) . "/templates/reg/Retrieve password-personal detail.html";
        exit;
    } else {
        header("Content-type: text/html; charset=utf-8");
        echo "<script>alert('验证邮件已过期,请重新发送验证邮件');window.top.location='/login.php'</script>";
        exit;
    }
} else {
    header("Content-type: text/html; charset=utf-8");
    echo "<script>alert('非法操作');window.top.location='/login.php'</script>";
    exit;
}
exit;
?>