<?php
ini_set("display_errors",0);
include_once dirname(__FILE__).'/data/db/DB.php';
include_once dirname ( __FILE__ ) . '/inc/global.inc.php';

//--------------判断是否登录-------------------//
$db = new DB ();
session_start();
$uid = $_SESSION['user_info']['id'];

//--------------判断是否登录-------------------//

//----------------验证是否是手机端登录-------------------//
$agent = $_SERVER['HTTP_USER_AGENT'];
if (strpos($agent, "NetFront") || strpos($agent, "iPhone") || strpos($agent, "MIDP-2.0") || strpos($agent, "Opera Mini") || strpos($agent, "UCWEB") || strpos($agent, "Android") || strpos($agent, "Windows CE") || strpos($agent, "SymbianOS")) {
	$is_mobile = true;
} else {
	$is_mobile = false;
}
//----------------验证是否是手机端登录-------------------//

if($uid) {
	$user_info = $db -> get_one("select * from f_member where id=".$uid);
	if($is_mobile) {
		$action = isset($_GET['action']) ? $_GET['action'] : '';
		if($action == 'wapchangename') {//修改用户名页面
			include_once dirname(__FILE__)."/templates/WapChangename.html";
		}else if($action == 'wapchangepwd') {//修改密码页面
			include_once dirname(__FILE__)."/templates/WapChangepwd.html";
		}else if($action == 'wapbindmobile') {//修改密码页面
			include_once dirname(__FILE__)."/templates/WapBindmobile.html";
		}else if($action == 'ajax_wap_edit_nickname'){//修改用户名页面ajax提交处理
			$nickname = filter_char($_POST['nickname']);
			$new_uid = filter_char($_POST['uid']);
			com_edit_nickname($nickname,$new_uid,$uid,$db);
		}else if($action == 'ajax_wap_bind_phone'){//绑定手机页面ajax提交处理
			$phone = filter_char($_POST['phone']);
			$user_code = filter_char($_POST['user_code']);
			//验证验证码
			com_checkyzm($user_code);
			$db -> update('f_member', array('phone'=>$phone), 'id = '.$uid);
			echo json_encode(array('id'=>2,'msg'=>'手机绑定成功'));
			exit;	
		}else if($action == 'ajax_wap_edit_pwd'){//修改密码名页面ajax提交处理	
			$password = filter_char($_POST['password']);
			$new_pwd = filter_char($_POST['new_pwd']);
			$confirm_pwd = filter_char($_POST['confirm_pwd']);
			//验证密码
			wap_com_check_pwd($password,$new_pwd,$confirm_pwd,$uid,$db);			
			$user_code = filter_char($_POST['user_code']);
			//验证验证码
			com_checkyzm($user_code);
			//修改用户记录
			//-----------更新用户表相关数据-----------//
			$db -> update('f_member', array('password'=>md5($new_pwd)), 'id = '.$uid);
			unset($_SESSION['user_info']);
			echo json_encode(array('id'=>2,'msg'=>'密码修改成功,请重新登录'));
			exit;
		}else{
			include_once dirname(__FILE__)."/templates/WapInformation.html";
		}		
	} else {
		include_once dirname(__FILE__)."/templates/user_info.html";
	}	
}else{
    $jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
	header("Location:".$jump_url);	
}

/**
 * 功能：手机端修改用户名方法
 */
function com_edit_nickname($nickname,$new_uid,$uid,$db) {
	if($new_uid != $uid){
		echo json_encode(array('id'=>1,'msg'=>'不是当前登录用户操作，请重新登录'));
		exit;
	}
	if (!preg_match("/^[\x81-\xfe][\x40-\xfe]?/",$nickname) && !preg_match("/^[a-z][A_Z]?/",$nickname)) {
		echo json_encode(array('id'=>2,'msg'=>'用户名格式不对，以英文字母开头或汉字开头'));
		exit;
	} else {
		if(mb_strlen($nickname,'UTF8') < 4 ){
			echo json_encode(array('id'=>3,'msg'=>'用户名格式不对，至少为4个字符'));
			exit;
		} else if(mb_strlen($nickname,'UTF8') > 16 ) {
			echo json_encode(array('id'=>4,'msg'=>'用户名格式不对，最多为16个字符'));
			exit;
		}
		$db -> update('f_member', array('nickname'=>$nickname), 'id = '.$uid);
		echo json_encode(array('id'=>5,'msg'=>'修改成功'));
		exit;
	}
}
/**
 * 功能：密码验证
 * @param string $password
 * @param string $new_pwd
 * @param string $confirm_pwd
 * @param string $uid
 * @param obj $db
 */
function wap_com_check_pwd($password,$new_pwd,$confirm_pwd,$uid,$db) {
	$user_rs = $db -> get_one('select email,password from f_member where id = '.$uid);
	if($user_rs['password'] == md5($password)) {
		if(!preg_match('/^[0-9a-zA-Z-+_!@#$%^&*()]{6,16}$/i',$new_pwd)) {
			echo json_encode(array('id'=>4,'msg'=>'新密码格式不对，请重新输入'));
			exit;
		}else{
			if($new_pwd != $confirm_pwd) {
				echo json_encode(array('id'=>5,'msg'=>'两次密码输入不一致'));
				exit;
			}
		}
	} else {
		echo json_encode(array('id'=>3,'msg'=>'当前使用密码错误，请重新输入'));
		exit;
	}
}
?>