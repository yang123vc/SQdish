<?php
/*
 *  Copyright (c) 2014 The CCP project authors. All Rights Reserved.
 *
 *  Use of this source code is governed by a Beijing Speedtong Information Technology Co.,Ltd license
 *  that can be found in the LICENSE file in the root of the web site.
 *
 *   http://www.yuntongxun.com
 *
 *  An additional intellectual property rights grant can be found
 *  in the file PATENTS.  All contributing project authors may
 *  be found in the AUTHORS file in the root of the source tree.
 */
include_once "CCPRestSmsSDK.php";
//引入短信验证码类
include_once dirname(__FILE__).'/../../entity/SMSCode.php';
// 引入常量定义
include_once dirname(__FILE__).'/../../data/constant.php';

//主帐号,对应开官网发者主账号下的 ACCOUNT SID
$accountSid= 'aaf98f89493ff1d3014954ab55770c8d';

//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
$accountToken= 'cf8fa1253f5144b58c0f760383eda98a';

//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
$appId='8a48b551493ffbd4014954c07b2a0cdd';
//测试appid
//$appId='aaf98f89493ff1d3014954ab957b0c90';

//请求地址
//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
//生产环境（用户应用上线使用）：app.cloopen.com
$serverIP='app.cloopen.com';
//$serverIP='sandboxapp.cloopen.com';


//请求端口，生产环境和沙盒环境一致
$serverPort='8883';

//REST版本号，在官网文档REST介绍中获得。
$softVersion='2013-12-26';


/**
  * 发送模板短信
  * @param to 手机号码集合,用英文逗号分开
  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
  * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
  */       
function sendTemplateSMS($to,$datas,$tempId)
{
     // 初始化REST SDK
     global $accountSid,$accountToken,$appId,$serverIP,$serverPort,$softVersion;
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);


    
     // 发送模板短信
//     echo "Sending TemplateSMS to $to <br/>";
     $result = $rest->sendTemplateSMS($to,$datas,$tempId);
     if($result == NULL ) {
         echo "result error!";
//         break;
         exit();
     }
     if($result->statusCode!=0) {
         echo "error code :" . $result->statusCode . "<br>";
         echo "error msg :" . $result->statusMsg . "<br>";
         //TODO 添加错误处理逻辑
         echo 2;
     }else{
//         echo "Sendind TemplateSMS success!<br/>";
//         // 获取返回信息
//         $smsmessage = $result->TemplateSMS;
//         echo "dateCreated:".$smsmessage->dateCreated."<br/>";
//         echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
         //TODO 添加成功处理逻辑
        // 以下移动到处理成功逻辑

        $code = $datas[0];
        $currTime = getdate();
        $seconds = $currTime[0];

        $smsCode = new SMSCode($code, $seconds); // 把验证码，和发送时间保存到对象

        session_start();
        $_SESSION[SMS_CODE] = $smsCode;

        echo '1';
     }
}

//Demo调用 
		//**************************************举例说明***********************************************************************
		//*假设您用测试Demo的APP ID，则需使用默认模板ID 1，发送手机号是13800000000，传入参数为6532和5，则调用方式为           *
		//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");																		  *
		//*则13800000000手机号收到的短信内容是：【云通讯】您使用的是云通讯短信模板，您的验证码是6532，请于5分钟内正确输入     *
		//*********************************************************************************************************************
 //sendTemplateSMS("",array('',''),"");//手机号码，替换内容数组，模板ID
?>
