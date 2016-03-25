<?php
date_default_timezone_set("PRC");
//send_mail(第一个参数收件人地址，第二个参数邮件标题，第三个参数邮件内容)
//将下面发件人信息填写正确即可无需配置服务器

function send_mail($tomail, $subject, $body){
    require_once('class.phpmailer.php');
    $mail = new PHPMailer();
    $mail->IsSMTP(); 
    //$mail->SMTPDebug = 2;						//显示调试信息
    $mail->SMTPAuth = true;						
    $mail->SMTPSecure = "ssl";				//启用ssl加密
    $mail->Host = "smtp.qq.com";				//邮箱服务器名称
    $mail->Port = 465;							//邮箱服务端口
    $mail->Username = "363182860@qq.com";			//发件人邮箱地址
    $mail->Password = "vombolheexzicahh";					//发件人邮箱密码
    $mail->CharSet = "UTF-8";
    $mail->SetFrom('363182860@qq.com', '张三');		//发件人信息 邮箱地址，姓名
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($tomail, "");
    if (!$mail->Send())
    {
        $stat = 0;
    }
    else
    {
        $stat = 1;           
    }
    return $stat;
}

/*
QQ邮箱 POP3 和 SMTP 服务器地址设置如下：
邮箱 	POP3服务器（端口110） 	SMTP服务器（端口25）
qq.com 	pop.qq.com 	smtp.qq.com
SMTP服务器需要身份验证。

如果是设置POP3和SMTP的SSL加密方式，则端口如下：
POP3服务器（端口995）
SMTP服务器（端口465或587）。
*/
?>