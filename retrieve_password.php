<?php

error_reporting(E_ALL);
session_start();
$isw = 0; //伪静态配置  1开启 0关闭
date_default_timezone_set('Asia/Shanghai');
include dirname(__FILE__) . '/data/db/DB.php';
$db = new DB();
$action = isset($_GET['action']) ? $_GET['action'] : '';
$nickname = '';
$agent = $_SERVER['HTTP_USER_AGENT'];
$is_mobile = false;
if (strpos($agent, "NetFront") || strpos($agent, "iPhone") || strpos($agent, "MIDP-2.0") || strpos($agent, "Opera Mini") || strpos($agent, "UCWEB") || strpos($agent, "Android") || strpos($agent, "Windows CE") || strpos($agent, "SymbianOS")) {
    $is_mobile = true;
} else {
    $is_mobile = false;
}
if (!empty($action)) {
    switch ($action) {
        case 'mobile':
            $mobile = isset($_POST['passport']) ? $_POST['passport'] : '';
            if (is_register($mobile, 2, $nickname, $db)) {
                ob_start();
                @send_message($mobile);
                ob_end_clean();
            } else {
                header("Content-type: text/html; charset=utf-8");
                echo "<script>alert('用户未注册');window.top.location='/login.php'</script>";
                exit;
            }
            $opt = 2;
            include_once dirname(__FILE__) . "/templates/reg/Retrieve password-phone.html";
            return;
        case 'email':
            $email = isset($_POST['passport']) ? $_POST['passport'] : '';
            $mail_websit = explode('@', $email)[1];
            if (is_register($email, 1, $nickname, $db)) {
                send_email($email, $db);
            } else {
                header("Content-type: text/html; charset=utf-8");
                echo "<script>alert('用户未注册');window.top.location='/login.php'</script>";
                exit;
            }
            $opt = 1;
            include_once dirname(__FILE__) . "/templates/reg/Retrieve-password-email-tips.html";
            return;
        case 'wapmobile':
            $mobile = isset($_POST['passport']) ? $_POST['passport'] : '';
            $yzm = isset($_POST['code']) ? $_POST['code'] : '';
            if (isset($_SESSION['yzm']) && ($yzm*1 == $_SESSION['yzm']['code']*1) && $mobile==$_SESSION['yzm']['mobliecode']) {
                include_once dirname(__FILE__) . "/templates/WapForget2.html";
                return;
            } else {
                header("Content-type: text/html; charset=utf-8");
                echo "<script>alert('验证码不正确，请重新获取');window.top.location='/retrieve_password.php'</script>";
                exit;
            }
            return;
        case 'Getcheckyzm':
            $mobile = isset($_POST['passport']) ? $_POST['passport'] : '';
            ob_start();
            @send_message($mobile);
            ob_end_clean();
            echo json_encode(array('flag' => 1, 'msgbox' => '短信下发成功'));
            exit;
        case 'wapupdatepassword':
            $username = isset($_POST['username']) ? $_POST['username'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            update($username, $password, $db);
            exit;
        case 'update':
            $yzm = isset($_POST['code']) ? $_POST['code'] : '';
            if (isset($_POST['opt']) && $_POST['opt'] == 1) {
                $username = isset($_POST['username']) ? $_POST['username'] : '';
                $password = isset($_POST['password']) ? $_POST['password'] : '';
                update($username, $password, $db);
            } elseif (isset($_POST['opt']) && $_POST['opt'] == 2) {
                if (checkyzm($yzm)) {
                    $username = isset($_POST['username']) ? $_POST['username'] : '';
                    $password = isset($_POST['password']) ? $_POST['password'] : '';
                    update($username, $password, $db);
                }
            }
            return;
    }
    exit;
}
if (strpos($agent, "NetFront") || strpos($agent, "iPhone") || strpos($agent, "MIDP-2.0") || strpos($agent, "Opera Mini") || strpos($agent, "UCWEB") || strpos($agent, "Android") || strpos($agent, "Windows CE") || strpos($agent, "SymbianOS")) {
    include_once dirname(__FILE__) . "/templates/WapForget.html";
} else {
    include_once dirname(__FILE__) . "/templates/reg/Retrieve password.html";
};

//1. opt 邮箱注册 2. opt 手机注册  返回值bool true 未注册 false 已注册
function is_register($data, $opt = 1, &$nickname, $db) {
    $sql = '';
    if ($opt == 1) {
        $sql = "select * from f_member where email = '$data' ";
    } else if ($opt == 2) {
        $sql = "select * from f_member where phone = '$data' ";
    }
    $result = $db->get_one($sql);
    if (empty($result)) {
        return false;
    } else {
        $nickname = $result['nickname'];
        return true;
    }
}

//发送短信
function send_message($mobile) {
    include_once(dirname(__FILE__) . "/lib/CCPRestSmsSDK.php");
//主帐号,对应开官网发者主账号下的 ACCOUNT SID
    $accountSid = '8a48b55152114d540152115b2936003c';
//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    $accountToken = '4340948ee7754e2da647904ddd5bf010';
//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    $appId = 'aaf98f89521153040152115bd2850016';
//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
//生产环境（用户应用上线使用）：app.cloopen.com
    $serverIP = 'app.cloopen.com';
    $rand_num = substr(mt_rand(100000, time()), 0, 6);
    //$_SESSION['validate_code'] = $rand_num;
//请求端口，生产环境和沙盒环境一致
    $serverPort = '8883';
//REST版本号，在官网文档REST介绍中获得。
    $softVersion = '2013-12-26';
    unset($_SESSION['yzm']);
    $yzm['code'] = $rand_num;
    $yzm['mobliecode'] = $mobile;
    $yzm['yzmtime'] = time();
    $_SESSION['yzm'] = $yzm;
    /**
     * 发送模板短信
     * @param to 手机号码集合,用英文逗号分开
     * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
     * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
     */
    sendTemplateSMS($mobile, array($rand_num, '30'), 62731, $serverIP, $serverPort, $softVersion, $accountSid, $accountToken, $appId); //手机号码，替换内容数组，模板ID
}

function update($username, $password, $db) {
    $data = array('password' => md5($password));
    $result = $db->update('f_member', $data, "username = '$username'");
    if ($result > 0) {
        //修改密码后要求重新登录
        unset($_SESSION['user_info']);
        echo json_encode(array('flag' => 1, 'msgbox' => '密码修改成功'));
    } else {
        echo json_encode(array('flag' => 0, 'msgbox' => '密码修改失败'));
    }
    exit;
}

//发送邮件
function send_email($email, $db) {
    //秘钥标识
    $key = "wwwsqdishcom";
    $token = md5($key . time());
    $_SESSION['token'] = $token;
    require_once dirname(__FILE__) . "/lib/email.class.php";
    //******************** 配置信息 ********************************
    $smtpserver = "smtp.qq.com"; //SMTP服务器
    $smtpserverport = 25; //SMTP服务器端口
    $smtpusermail = "service@sqdish.com"; //SMTP服务器的用户邮箱
    $smtpemailto = $email; //发送给谁
    $smtpuser = "service@sqdish.com"; //SMTP服务器的用户帐号
    $smtppass = "159Qsc159"; //SMTP服务器的用户密码
    $mailtitle = '请修改您的密码'; //邮件主题
    $mailcontent = "<h1>欢迎注册食器，请点击链接完成修改密码</h1><br><a href='http://www.sqdish.com/retrieve_password_email.php?email=$email&token=$token'>http://www.sqdish.com/retrieve_password_email.php</a></br>"; //邮件内容
    $mailtype = "HTML"; //邮件格式（HTML/TXT）,TXT为文本邮件
    //************************ 配置信息 ****************************
    $smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false; //是否显示发送的调试信息
    $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
    $validate_date = array('tocken' => $token, 'send_time' => time(), 'email' => $email);
    //插入邮箱验证信息
    $db->insert('f_email_tocken', $validate_date);
}

function checkyzm($yzm) {
    $result = array();
    if (empty($yzm)) {
        $result = array('flag' => 0, 'msgbox' => '验证码不能为空');
    } else {
        if (isset($_SESSION['yzm'])) {
            if ($_SESSION['yzm']['code'] == $yzm) {
                $result = array('flag' => '1', 'msgbox' => '验证成功');
                return true;
            } else {
                $result = array('flag' => '0', 'msgbox' => "验证码已过期，或验证码不正确");
            }
        } else {
            $result = array('flag' => 0, 'msgbox' => '请重新获取验证码');
        }
    }
    echo json_encode($result);
    exit;
}

function sendTemplateSMS($to, $datas, $tempId, $serverIP, $serverPort, $softVersion, $accountSid, $accountToken, $appId) {
    // 初始化REST SDK
    $rest = new REST($serverIP, $serverPort, $softVersion);
    $rest->setAccount($accountSid, $accountToken);
    $rest->setAppId($appId);

    // 发送模板短信
    $result = $rest->sendTemplateSMS($to, $datas, $tempId);
    if ($result == NULL) {
        echo "result error!";
    }
    if ($result->statusCode != 0) {
        echo "error code :" . $result->statusCode . "<br>";
        echo "error msg :" . $result->statusMsg . "<br>";
        //TODO 添加错误处理逻辑
    } else {
        echo "Sendind TemplateSMS success!<br/>";
        // 获取返回信息
        $smsmessage = $result->TemplateSMS;
        echo "dateCreated:" . $smsmessage->dateCreated . "<br/>";
        echo "smsMessageSid:" . $smsmessage->smsMessageSid . "<br/>";
        //TODO 添加成功处理逻辑
    }
    return $result;
}

?>
