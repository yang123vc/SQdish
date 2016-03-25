<?php 
ini_set("display_errors",0);
include_once dirname(__FILE__).'/data/db/DB.php';
include_once dirname(__FILE__).'/inc/global.inc.php';
session_start();

$user_info = get_user_info();
@$uid = $user_info['id'];

$flag = isset($_POST['flag']) ? $_POST['flag'] : '';
$db = new DB();
switch ($flag) {
	case 1 : //账号设置=>基本信息修改
		//---------获取基本信息参数-------------//
		$nickname = filter_char(urldecode($_POST['nickname']));
		$gender = filter_char($_POST['gender']);
		$birthday = filter_char(urldecode($_POST['birthday']));
		$city = filter_char(urldecode($_POST['city']));
		//---------获取基本信息参数-------------//		
		if(isAjax()) {
			if(!empty($user_info)) {
				//-----------更新用户表相关数据-----------//
				$new_array = array('nickname'=>$nickname,'gender'=>$gender,'birthday'=>$birthday,'city'=>$city);
				$update_flag = $db -> update('f_member', $new_array, 'id = '.$uid);
				if($update_flag) {
					echo json_encode(array('id'=>4,'msg'=>'用户信息保存成功'));
					exit;
				}else{
					echo json_encode(array('id'=>3,'msg'=>'请先登录再操作'));
					exit;
				}
				//-----------更新用户表相关数据-----------//
			}else{
				echo json_encode(array('id'=>2,'msg'=>'请先登录再操作'));
				exit;
			}
		} else {
			echo json_encode(array('id'=>1,'msg'=>'请求出错，请联系管理员'));
			exit;
		}
		break;
	case 2 : //账号设置=>通过邮箱修改密码
		if(isAjax()) {
			if(!empty($user_info)) {				
				$password = filter_char($_POST['password']);
				$new_pwd = filter_char($_POST['new_pwd']);
				$confirm_pwd = filter_char($_POST['confirm_pwd']);
				com_check_pwd($password,$new_pwd,$confirm_pwd,$uid);							
			}else{
				echo json_encode(array('id'=>2,'msg'=>'请先登录再操作'));
				exit;
			}
		}else{
			echo json_encode(array('id'=>1,'msg'=>'请求出错，请联系管理员'));
			exit;
		}
		break;
	case 3://修改密码=>通过手机验证修改=>已经绑定手机
		if(isAjax()) {
			if(!empty($user_info)) {
				$password = filter_char($_POST['password']);
				$new_pwd = filter_char($_POST['new_pwd']);
				$confirm_pwd = filter_char($_POST['confirm_pwd']);
				//验证密码
				com_check_pwd($password,$new_pwd,$confirm_pwd,$uid);
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
				echo json_encode(array('id'=>1,'msg'=>'请先登录再操作'));
				exit;
			}
		}else{
			echo json_encode(array('id'=>0,'msg'=>'请求出错，请联系管理员'));
			exit;
		}
		break;
	case 'code'://获取手机短信验证码
		if(isAjax()) {
			if(!empty($user_info)) {
				$phone = $_POST['phone'];
				if(!preg_match('/^1\d{10}$/',$phone)) {
					echo json_encode(array('id'=>'error','msg'=>'当前手机号码格式不正确，请联系管理员'));
					exit;
				}else{
					try {
						ob_start();
						com_send_message($phone);
						ob_end_clean();
						echo json_encode(array('id'=>'ok','msg'=>'获取验证码成功，请注意查收短信'));
						exit;
					} catch (Exception $e) {
						echo json_encode(array('id'=>'error','msg'=>'获取验证码失败，请联系管理员'));
						exit;
					}
				}
			}else{
				echo json_encode(array('id'=>'error','msg'=>'请重新登录'));
				exit;
			}
		}else{
			echo json_encode(array('id'=>'error','msg'=>'请求出错，请联系管理员'));
			exit;
		}
		break;
	case 'bindphone':
		if(isAjax()) {
			if(!empty($user_info)) {
				$verify_from = $_POST['verify_from']; //1账户安全-绑定手机2账户安全-更改手机3账户安全-解除绑定手机
				
				$phone = filter_char($_POST['phone']);
				$user_code = filter_char($_POST['user_code']);
				//验证验证码
				com_checkyzm($user_code);
				//-----------更新用户表相关数据-----------//
				if($verify_from == 1){
					$db -> update('f_member', array('phone'=>$phone), 'id = '.$uid);
					$msg = '绑定成功';
				} else if($verify_from == 2) {
					$db -> update('f_member', array('phone'=>$phone), 'id = '.$uid);
					$msg = '更改手机成功';
				} else if($verify_from == 3) {
					$db -> update('f_member', array('phone'=>''), 'id = '.$uid);
					$msg = '解除绑定成功';
				} else {
					$msg = '页面出错，请联系管理员';
				}				
				echo json_encode(array('id'=>2,'msg'=>$msg));
				exit;
			}else{
				echo json_encode(array('id'=>1,'msg'=>'会话过期，请重新登录'));
				exit;
			}
		}else{
			echo json_encode(array('id'=>0,'msg'=>'请求出错，请联系管理员'));
			exit;
		}
		break;
	case 'bindemailone'://绑定邮箱身份验证页面
		if(isAjax()) {
			if(!empty($user_info)) {
				$phone = filter_char($_POST['phone']);
				$user_code = filter_char($_POST['user_code']);
				//验证验证码
				com_checkyzm($user_code);
				echo json_encode(array('id'=>2,'msg'=>'验证成功'));
				exit;
			}else{
				echo json_encode(array('id'=>1,'msg'=>'会话过期，请重新登录'));
				exit;
			}
		}else{
			echo json_encode(array('id'=>0,'msg'=>'请求出错，请联系管理员'));
			exit;
		}
		break;
	case 'bindemailtwo':
		if(isAjax()) {
			if(!empty($user_info)) {
				$email = filter_char($_POST['email']);
				if(!preg_match("/^([a-zA-Z0-9]+[\-|_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/",$email)){
					echo json_encode(array('id'=>2,'msg'=>'邮箱格式不正确，请重新输入'));
					exit;
				}
				//发送邮件
				$token = md5($uid . $email . time());
				$_SESSION['verifyemail'] = $token;
				$mailtitle = '绑定邮箱验证';
				$mailurl = 'http://'.$_SERVER['HTTP_HOST'].'/account_security.php?action=verifyemail&id='.$uid.'&email='.$email.'&token='.$token;
				$mailcontent = '<h1>请点击下面的链接完成邮箱验证</h1><br><a href="'.$mailurl.'">'.$mailurl.'</a>';
				$state = com_send_email($email,$mailtitle,$mailcontent);
				if($state) {
					echo json_encode(array('id'=>4,'msg'=>'邮件已发送至您的邮箱:'.$email.'</span><br />
			      验证邮件24小时内有效,请尽快登录您的邮箱点击验证链接验证'));
					exit;
				} else {//邮件发送失败，直接跳到修改密码页面
					echo json_encode(array('id'=>3,'msg'=>'邮件发送失败，请联系管理员'));
					exit;
				}
			}else{
				echo json_encode(array('id'=>1,'msg'=>'会话过期，请重新登录'));
				exit;
			}
		}else{
			echo json_encode(array('id'=>0,'msg'=>'请求出错，请联系管理员'));
			exit;
		}
		break;
	default :
		$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/edit_password.php';
		header("Location:".$jump_url);		
}
/**
 * 功能：用户设置中->公用的修改密码判断
 * @param string $password 当前密码
 * @param string $new_pwd 新密码
 * @param string $confirm_pwd 确认新密码
 * @param int $uid 用户ID
 * 
 * @author prh
 */
function com_check_pwd($password,$new_pwd,$confirm_pwd,$uid) {
	$db = new DB();
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