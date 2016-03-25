<?php

error_reporting(E_ALL);
session_start();
 if (isset($_SESSION['user_info'])) {
        header('Location: ' . 'http://www.sqdish.com/index.php');
        exit;
    }
$isw = 0; //伪静态配置  1开启 0关闭
date_default_timezone_set('Asia/Shanghai');
include dirname(__FILE__) . '/data/db/DB.php';
$action = isset($_GET['action']) ? $_GET['action'] : '';
//会员中心跳转
$url = isset($_GET['from']) ? urldecode($_GET['from']) : '';
$is_mobile = false;
$agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($agent, "NetFront") || strpos($agent, "iPhone") || strpos($agent, "MIDP-2.0") || strpos($agent, "Opera Mini") || strpos($agent, "UCWEB") || strpos($agent, "Android") || strpos($agent, "Windows CE") || strpos($agent, "SymbianOS")) {
    $is_mobile = true;
} else {
    $is_mobile = false;
}
if (!empty($action)) {
    switch ($action) {
        case 'login':
            login_validate($url);
            break;
        case 'weixinLogin':
            wechat_login($is_mobile);
            break;
        case 'authwechat':
            wechat_auth($is_mobile);
            break;
    }
}
if($is_mobile)
{
	include_once dirname(__FILE__) . "/templates/WapLogin.html";
}else{
include_once dirname(__FILE__) . "/templates/reg/login.html";
}
exit;

function login_validate($url) {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $username = trim($username);
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $password = trim($password);
    $result = array();
    if (!empty($username) && !empty($password)) {
        $db = new DB();
        $sql = "select * from f_member where username = '$username' ";
        $data = $db->get_one($sql);
        if ($data) {
            if (md5($password) == $data['password']) {
                $_SESSION['user_info'] = $data;
                $returnUrl =  isset($_SESSION['returnUrl'])?$_SESSION['returnUrl']:null;
                $_SESSION['returnUrl'] = null;
                $result = array('flag' => 1,'returnUrl'=>$returnUrl, 'msgbox' => '验证登录成功');
                if(!empty($url))
                {
	             $result = array('flag' => 2,'url'=>$url, 'msgbox' => '验证登录成功');   
                }
               
            } else {
                $result = array('flag' => 0, 'msgbox' => '密码错误');
            }
        } else {
            $result = array('flag' => 0, 'msgbox' => '无此用户');
        }
        echo json_encode($result);
        exit;
    } else {
        $result = array('flag' => 0, 'msgbox' => '用户名或密码为空');
    }

    echo json_encode($result);
    exit;
}

function wechat_login($is_mobile) {
    if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['wechat_openid'])) {
        $_SESSION['user_info']['unionid'] = $_SESSION['user_info']['wechat_openid'];
        $this->userinfo($_SESSION['user_info']);
        exit;
    }
    $redirect_url = urlencode("http://www.sqdish.com/login.php?action=authwechat");
    $url = "https://open.weixin.qq.com/connect/qrconnect?appid=wx98713a8add78cd73&redirect_uri=$redirect_url&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
    if($is_mobile){
      $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx3ca7b87e788210b7&redirect_uri=$redirect_url&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
    }
    header('Location: ' . $url);
}

function wechat_auth($is_mobile) {
    //避免使用过期的Code重复请求api致使出现错误
    if (isset($_SESSION['user_info']) && !empty($_SESSION['user_info']['wechat_openid'])) {
        header('Location: ' . 'http://www.sqdish.com/index.php');
        exit;
    }
    //1. 判断参数
    $code = '';
    if (isset($_GET['code'])) {
        $code = $_GET['code'];
    }
    //2. 使用微信返回的code获取access_tocken
    if (!empty($code)) {
        if ($is_mobile) {
                    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx3ca7b87e788210b7&secret=38f3524d218b10520ee864dd3112386d&code=$code&grant_type=authorization_code";
        }else{
                    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx98713a8add78cd73&secret=bdc04416a67c9ca246975c1509405ad0&code=$code&grant_type=authorization_code";
        }

        $data = file_get_contents($url);

        $result = json_decode($data, true);
           //print_r($result);exit;
        if (isset($result['access_token'])) {
            $accsee_token = $result['access_token'];
            $openid = $result['openid'];
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$accsee_token&openid=$openid";
            $data = file_get_contents($url);
            $result = json_decode($data, true);
               //print_r($result);exit;
            userinfo($result);
        } else {
            header('Location: ' . 'http://www.sqdish.com/index.php');
            exit;
        }
    } else {
        header('Location: ' . 'http://www.sqdish.com/index.php');
        exit;
    }
}

function userinfo($result) {
    $weixincode = $result['unionid'];
    $user_info['opt'] = 3;
    $user_info['wechat_openid'] = $result['unionid'];
    $user_info['open_id'] = $result['openid'];
    $user_info['reg_time'] = time();
    $user_info['username'] = $result['unionid'];
    $user_info['password'] = md5($result['unionid']);
    $user_info['nickname'] = $result['nickname'];
    $user_info['thumbail'] = $result['headimgurl'];
    $db = new DB();
    $sql = "select * from f_member where wechat_openid = '$weixincode' ";
    $data = $db->get_one($sql);
    if ($data) {
        $_SESSION['user_info'] = $data;
        $cart = $_SESSION['cart'];
        if (!empty($cart)) {
                header('Location: ' . 'http://www.sqdish.com/dish/check.php');
                exit;
        }else{
                header('Location: ' . 'http://www.sqdish.com/index.php');
                exit;
        }
    }else{
          $res = $db->insert('f_member', $user_info);
            if ($res > 0) {
                $sql = "select * from f_member where wechat_openid = '$weixincode' ";
                 $data = $db->get_one($sql);
                //$_SESSION['user_info'] = $user_info;
                $_SESSION['user_info'] = $data;
                $cart = $_SESSION['cart'];
                if (!empty($cart)) {
                        header('Location: ' . 'http://www.sqdish.com/dish/check.php');
                        exit;
                }else{
                        header('Location: ' . 'http://www.sqdish.com/index.php');
                        exit;
                }
         
            }
    }

}

?>
