<?php

session_start();
$isw = 0; //伪静态配置  1开启 0关闭
date_default_timezone_set('Asia/Shanghai');
include dirname(__FILE__) . '/data/db/DB.php';
$db = new DB();
$action = isset($_GET['action']) ? $_GET['action'] : '';
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
            if (is_register($mobile, 2, $db)) {
                ob_start();
                @send_message($mobile);
                ob_end_clean();
            } else {
                header("Content-type: text/html; charset=utf-8");
                echo "<script>alert('手机已经注册');window.top.location='/login.php'</script>";
                exit;
            }
            $opt = 2;
            include_once dirname(__FILE__) . "/templates/reg/login_phone.html";
            return;
        case 'wapmobile':
            $mobile = isset($_POST['passport']) ? $_POST['passport'] : '';
            $yzm = isset($_POST['code']) ? $_POST['code'] : '';
            if (isset($_SESSION['yzm']) && ($yzm*1 == $_SESSION['yzm']['code']*1) && $mobile==$_SESSION['yzm']['mobliecode']) {
                include_once dirname(__FILE__) . "/templates/WapMoblieReg2.html";
                return ;
            } else {
                header("Content-type: text/html; charset=utf-8");
                echo "<script>alert('验证码不正确，请重新获取');window.top.location='/register.php'</script>";
                exit;
            }
            return;
        case 'Getcheckyzm':
            $mobile = isset($_POST['passport']) ? $_POST['passport'] : '';
            // if (is_register($mobile, 2, $db)) {
            ob_start();
            @send_message($mobile);
            ob_end_clean();
            //  }
            echo json_encode(array('flag' => 1, 'msgbox' => '短信下发成功'));
            exit;
        case 'email':
            $email = isset($_POST['passport']) ? $_POST['passport'] : '';
            $mail_websit = explode('@', $email)[1];
            if (is_register($email, 1, $db)) {
                send_email($email, $db);
            } else {
                header("Content-type: text/html; charset=utf-8");
                echo "<script>alert('邮箱已注册');window.top.location='/login.php'</script>";
                exit;
            }
            $opt = 1;
            include_once dirname(__FILE__) . "/templates/reg/email_tips.html";
            return;
        case 'qqLogin':
            $request = file_get_contents('php://input', 'r');
            $user_info = get_UserInfoByQQ($request, $db);
            register($user_info, $db);
            exit;
        case 'wapregister':
            $user_info = preWapRegister($_POST, $db);
            register($user_info, $db);
            exit;
        case 'register':
            $yzm = isset($_POST['code']) ? $_POST['code'] : '';
            $user_info = preRegister($_POST, $db);
            if (isset($_POST['opt']) && $_POST['opt'] == 1) {
                register($user_info, $db);
            } elseif (isset($_POST['opt']) && $_POST['opt'] == 2) {
                if (checkyzm($yzm)) {
                    register($user_info, $db);
                }
            }
            return;
    }
    exit;
}
if ($is_mobile) {
    include_once dirname(__FILE__) . "/templates/WapMoblieReg.html";
} else {
    include_once dirname(__FILE__) . "/templates/reg/register.html";
}

//1. opt 邮箱注册 2. opt 手机注册  返回值bool true 未注册 false 已注册
function is_register($data, $opt = 1, $db) {
    $sql = '';
    if ($opt == 1) {
        $sql = "select * from f_member where email = '$data' ";
    } else if ($opt == 2) {
        $sql = "select * from f_member where phone = '$data' ";
    }
    $result = $db->get_one($sql);
    if (empty($result)) {
        return true;
    } else {
        return false;
    }
}

function preWapRegister($data, $db) {
    $validate = '';
    $user_info['opt'] = trim($data['opt']);
    $user_info['reg_time'] = time();
    $user_info['username'] = trim($data['phone']);
    $user_info['phone'] = trim($data['phone']);
    $validate = $user_info['phone'];
    $user_info['password'] = md5(trim($data['password']));
    $user_info['nickname'] = trim($data['nickname']);
    $user_info['thumbail'] = '';

    if (!is_register($validate, $user_info['opt'], $db)) {
        echo "<script>alert('用户已注册');</script>";
        exit;
    }
    return $user_info;
}

function preRegister($data, $db) {
    $validate = '';
    $user_info['opt'] = trim($data['opt']);
    $user_info['reg_time'] = time();
    if ($user_info['opt'] == 2) {
        $user_info['username'] = trim($data['phone']);
        $user_info['phone'] = trim($data['phone']);
        $validate = $user_info['phone'];
    } else if ($user_info['opt'] == 1) {
        $user_info['username'] = trim($data['email']);
        $user_info['email'] = trim($data['email']);
        $validate = $user_info['email'];
    }
    $user_info['password'] = md5(trim($data['password']));
    $user_info['nickname'] = trim($data['nickname']);
    $user_info['thumbail'] = '';

    if (!is_register($validate, $user_info['opt'], $db)) {
        echo json_encode(array('flag' => 0, 'msgbox' => '用户已注册'));
        exit;
    }
    return $user_info;
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

//发送邮件
function send_email($email,$db) {
    //秘钥标识
    $key = "wwwsqdishcom";
    $token = md5($key . time());
    require_once dirname(__FILE__) . "/lib/email.class.php";
    //******************** 配置信息 ********************************
    $smtpserver = "smtp.qq.com"; //SMTP服务器
    $smtpserverport = 25; //SMTP服务器端口
    $smtpusermail = "service@sqdish.com"; //SMTP服务器的用户邮箱
    $smtpemailto = $email; //发送给谁
    $smtpuser = "service@sqdish.com"; //SMTP服务器的用户帐号
    $smtppass = "159Qsc159"; //SMTP服务器的用户密码
    $mailtitle = '请激活你的食器账号'; //邮件主题
    $mailcontent = "<h1>欢迎注册食器，请点击链接完成注册</h1><br><a href='http://www.sqdish.com/register_email.php?email=$email&token=$token'>http://www.sqdish.com/register_email.php</a></br>"; //邮件内容
    $mailtype = "HTML"; //邮件格式（HTML/TXT）,TXT为文本邮件
    //************************ 配置信息 ****************************
    $smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
    $smtp->debug = false; //是否显示发送的调试信息
    $state = $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
    $validate_date = array('tocken'=> $token, 'send_time'=>time(), 'email'=>$email);
    //插入邮箱验证信息
    $db->insert('f_email_tocken', $validate_date);
    
}

//根据QQ返回的值注册用户  若用户已经存在则跳转到首页，记入$_SESSION['user_info']
function get_UserInfoByQQ($request, $db) {
    $user_info = array();
    $temp_arr = json_decode($request, true);
    $Q_OpenId = isset($temp_arr['openid']) ? $temp_arr['openid'] : '';
    if (!empty($Q_OpenId)) {
        $sql = "select * from f_member where qq_openid = '$Q_OpenId' ";
        $user_info = $db->get_one($sql);
        if ($user_info) {
            $_SESSION['user_info'] = $user_info;
            //用户信息已经有了，跳转到首页
            echo json_encode(array('flag' => 1, 'msg' => '用户已存在'));
            exit;
        } else {
            $user_info['opt'] = 4;
            $user_info['qq_openid'] = $Q_OpenId;
            $user_info['reg_time'] = time();
            $user_info['username'] = $Q_OpenId;
            $user_info['password'] = md5($Q_OpenId);
            $user_info['nickname'] = $temp_arr['nickname'];
            $user_info['thumbail'] = $temp_arr['figureurl'];
            return $user_info;
        }
    }
}

function register($user_info, $db) {
    $result = $db->insert('f_member', $user_info);
    if ($result > 0) {
        $_SESSION['user_info'] = $user_info;
        echo json_encode(array('flag' => 1, 'msgbox' => '注册成功'));
    } else {
        echo json_encode(array('flag' => 0, 'msgbox' => '注册失败'));
    }
    exit;
}

function checkyzm($yzm) {
    if (empty($yzm)) {
        $data = array('flag' => 0, 'msgbox' => '验证码不能为空');
    } else {
        if (isset($_SESSION['yzm'])) {
            if ($_SESSION['yzm']['code'] == $yzm) {
                $data = array('flag' => '1', 'msgbox' => '验证成功');
                return true;
            } else {
                $data = array('flag' => '0', 'msgbox' => "验证码已过期，或验证码不正确");
            }
        } else {
            $data = array('flag' => 0, 'msgbox' => '请重新获取验证码');
        }
    }
    echo json_encode($data);
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
