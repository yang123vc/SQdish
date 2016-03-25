<?php
/**
 *
 * 功能：过滤非法字符
 * 
 * @author prh
 */
function filter_char($content) {
	$arr = array (
			"!<([/]?)(htm|head|body|meta|form|applet|object)([^>]+)?>!is" => '',
			"!<style(.+?)?>(.+?)?</style>!is" => '',
			"!<script(.+?)?>(.+?)?</script>!is" => '',
			"!<title(.+?)?>(.+?)?</title>!is" => '',
			"/javascript/i" => "&#106avascript",
			"/(about|file):/i" => "$1&#58",
			"/document.cookie/i" => "documents&#46cookie",
			'/ on([a-z]+)([ ]+)=/i' => ' &#111n$1$2=',
			"!<([/]?)(htm|head|body|meta|form|applet|object)(.+?)?>!is" => '< $1$2>',
			"/'/" => "&quot;",
			"/<script[^>]+>.+?<\/script>/" => "",
			"/<script[^>]+>.+?<\/script>/" => "",
			"/select|insert|update|delete|union|into|load_file|outfile|group_contact/" => '' 
	);
	return $content = trim ( preg_replace ( array_keys ( $arr ), array_values ( $arr ), $content ) );
	/*
	 * $farr = array(
	 * "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
	 * "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
	 * "/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|dump/is"
	 * );
	 */
}
/**
 * 功能：判断是否是ajax请求
 *
 * @return boolean
 *
 * @author prh
 */
function isAjax() {
	if (isset ( $_SERVER ['HTTP_X_REQUESTED_WITH'] ) && $_SERVER ['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")
		return true;
	return false;
}
/**
 * 功能：截取字符串
 *
 * @param $sourcestr 要截取的字符串        	
 * @param $cutlength 要截取的中文字数(不是字节数)
 *        	1个中文字=2个英文字 即$length=1时显示汉字是1个显示英文是2个
 * @return string 返回值后边已带...，不需要另外拼接。
 *        
 * @author prh
 */
function CutStr($sourcestr, $cutlength) {
	$returnstr = '';
	$i = 0;
	$n = 0;
	$str_length = strlen ( $sourcestr ); // 字符串的字节数
	while ( ($n < $cutlength) and ($i <= $str_length) ) {
		$temp_str = substr ( $sourcestr, $i, 1 );
		$ascnum = Ord ( $temp_str ); // 得到字符串中第$i位字符的ascii码
		if ($ascnum >= 224) // 如果ASCII位高与224，
{
			$returnstr = $returnstr . substr ( $sourcestr, $i, 3 ); // 根据UTF-8编码规范，将3个连续的字符计为单个字符
			$i = $i + 3; // 实际Byte计为3
			$n ++; // 字串长度计1
		} elseif ($ascnum >= 192) // 如果ASCII位高与192，
{
			$returnstr = $returnstr . substr ( $sourcestr, $i, 2 ); // 根据UTF-8编码规范，将2个连续的字符计为单个字符
			$i = $i + 2; // 实际Byte计为2
			$n ++; // 字串长度计1
		} elseif ($ascnum >= 65 && $ascnum <= 90) // 如果是大写字母，
{
			$returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
			$i = $i + 1; // 实际的Byte数仍计1个
			$n ++; // 但考虑整体美观，大写字母计成一个高位字符
		} else // 其他情况下，包括小写字母和半角标点符号，
{
			$returnstr = $returnstr . substr ( $sourcestr, $i, 1 );
			$i = $i + 1; // 实际的Byte数计1个
			$n = $n + 0.5; // 小写字母和半角标点等与半个高位字符宽...
		}
	}
	if ($str_length > strlen ( $returnstr )) {
		$returnstr = $returnstr . "..."; // 超过长度时在尾处加上省略号
	}
	return $returnstr;
}
/**
 * 功能：发送邮件
 *
 * @param string $email 
 * @return int $state       	
 *
 */
function com_send_email($email, $mailtitle, $mailcontent) {
	
	require_once "/lib/email.class.php";
	
	// ******************** 配置信息 ********************************
	$smtpserver = "smtp.126.com"; // SMTP服务器
	$smtpserverport = 25; // SMTP服务器端口
	$smtpusermail = "zihuang1260609@126.com"; // SMTP服务器的用户邮箱
	$smtpemailto = $email; // 发送给谁
	$smtpuser = "zihuang1260609@126.com"; // SMTP服务器的用户帐号
	$smtppass = "huanwuai@123"; // SMTP服务器的用户密码
	$mailtype = "HTML"; // 邮件格式（HTML/TXT）,TXT为文本邮件
	// ************************ 配置信息 ****************************
	
	$smtp = new smtp ( $smtpserver, $smtpserverport, true, $smtpuser, $smtppass ); // 这里面的一个true是表示使用身份验证,否则不使用身份验证.
	$smtp->debug = false; // 是否显示发送的调试信息
	$state = $smtp->sendmail ( $smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype );
	return $state;
}
/**
 * 功能：发送短信
 * 
 * @param string $mobile
 */
function com_send_message($mobile) {
	include_once("/lib/CCPRestSmsSDK.php");
//主帐号,对应开官网发者主账号下的 ACCOUNT SID
    $accountSid = '8a48b55152114d540152115b2936003c';
//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    $accountToken = '4340948ee7754e2da647904ddd5bf010';
//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    $appId = '8a48b55152114d540152115b609b003e';
//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
//生产环境（用户应用上线使用）：app.cloopen.com
    $serverIP = 'sandboxapp.cloopen.com';
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
	sendTemplateSMS($mobile, array($rand_num, '30'), 1, $serverIP, $serverPort, $softVersion, $accountSid, $accountToken, $appId); //手机号码，替换内容数组，模板ID
}
function sendTemplateSMS($to, $datas, $tempId, $serverIP, $serverPort, $softVersion, $accountSid, $accountToken, $appId) {
    // 初始化REST SDK
    $rest = new REST($serverIP, $serverPort, $softVersion);
    $rest->setAccount($accountSid, $accountToken);
    $rest->setAppId($appId);

    // 发送模板短信
    $result = $rest->sendTemplateSMS($to, $datas, $tempId);
    if ($result == NULL) {
        //echo "result error!";
    }
    if ($result->statusCode != 0) {
        //echo "error code :" . $result->statusCode . "<br>";
        //echo "error msg :" . $result->statusMsg . "<br>";
        //TODO 添加错误处理逻辑
    } else {
        //echo "Sendind TemplateSMS success!<br/>";
        // 获取返回信息
        $smsmessage = $result->TemplateSMS;
        //echo "dateCreated:" . $smsmessage->dateCreated . "<br/>";
        //echo "smsMessageSid:" . $smsmessage->smsMessageSid . "<br/>";
        //TODO 添加成功处理逻辑
    }
    return $result;
}
/**
 * 功能：短信验证码验证
 * @param string $yzm
 */
function com_checkyzm($yzm) {
	if (empty($yzm)) {
		$data = array('id' => 0, 'msg' => '验证码不能为空');
		echo json_encode($data);
		exit;
	} else {
		if (isset($_SESSION['yzm'])) {
			if ($_SESSION['yzm']['code'] == $yzm) {
				$data = array('id' => 1, 'msg' => '验证成功');
			} else {
				$data = array('id' => 0, 'msg' => "验证码已过期，或验证码不正确");
				echo json_encode($data);
				exit;
			}
		} else {
			$data = array('id' => 0, 'msg' => '请重新获取验证码');
			echo json_encode($data);
			exit;
		}
	}	
}
/**
 * 功能：获取登录后信息
 * 
 * @return array
 */
function get_user_info() {
	session_start();
	$username= $_SESSION['user_info']['username'];
	if($username) {
		$db = new DB();
		$user_info = $db -> get_one("select * from f_member where username='".$username."'");
		return !empty($user_info) ? $user_info : array();
	} else {
		return array();
	}
}
?>