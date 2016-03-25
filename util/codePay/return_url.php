<?php
/* * 
 * 功能：支付宝页面跳转同步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

 *************************页面功能说明*************************
 * 该页面可在本机电脑测试
 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
 */

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

include_once dirname(__FILE__).'/../../data/constant.php';
include_once dirname(__FILE__).'/../../data/db/config.php';

include_once dirname(__FILE__).'/../pay-log.php';
include_once dirname(__FILE__).'/../../data/db/MysqliDb.php';
$db = new MysqliDb($db_config['hostname'], $db_config["username"], $db_config["password"], $db_config["database"]);
?>
<!DOCTYPE HTML>
<html>
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="<?php echo PARTNER_ROOT; ?>/assets/js/jquery-1.11.0.min.js"></script>
<?php
//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代码
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

	//商户订单号

	$out_trade_no = $_GET['out_trade_no'];

	//支付宝交易号

	$trade_no = $_GET['trade_no'];

	//交易状态
	$trade_status = $_GET['trade_status'];
        
        // 交易金额
        $total_fee = $_GET['total_fee'];
        
        
    if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
        
        // 判断交易单号与金额能否对上
        $db->where("order_no", $out_trade_no);
        $result = $db->getOne("xt_code_order");
        $pid = $result['partner_id'];
        $amount = $result['amount'];
        $price = $result['money']/$amount;

        if($result['money'] == $total_fee){
            $params = array("status"=>1, "pay_way"=>"alipay", "pay_time"=>$db->now());
            $db->where("order_no", $out_trade_no);
            $db->where("status", 0);
            if($db->update("xt_code_order", $params)){
                writeLog("codPayLog", $out_trade_no, $trade_no, $trade_status, $total_fee, "RETURN");

                echo "验证通过，激活码生成中, 请稍后...<br />";

                echo "<script type='text/javascript'>
                            $(document).ready(function(){
                                $.ajax({
                                  type: 'post',
                                  url: '".PARTNER_ROOT."/action/get-activation-code.php',
                                  data:{'pid': ".$pid." , 'amount': ".$amount.", 'price':".$price.", 'order_no': '".$out_trade_no."'},
                                  async:true,
                                  success: function(data){
                                      if(data == 'success'){
                                        alert('激活码生成完毕');
                                        window.location.href='".PARTNER_ROOT."/partner-code-list.php';
                                      } else{
                                          alert('生成失败');
                                      }
                                  }
                                });
                            });

                      </script>";
//                // 1. 初始化
//                $ch = curl_init();
//                // 2. 设置选项，包括URL
//                curl_setopt($ch, CURLOPT_URL, "http://".$_SERVER['SERVER_NAME'].ADMIN_ROOT."/action/get-activation-code.php?pid=".$result['partner_id']."&amount=".$result['amount']."&price=".$result['money']);
//                // 3. 执行并获取HTML文档内容
//                curl_exec($ch);
//                // 4. 释放curl句柄
//                curl_close($ch);

//                ."<script type='text/javascript'>window.location.href='http://".$_SERVER['SERVER_NAME'].PARTNER_ROOT."/partner-order-list.php'</script>";
            } else{
                echo "订单状态修改失败，如您已支付成功，请联系网站管理员!";
            }

        } else{
            echo "验证失败，跳转中...<br />"
            ."<script type='text/javascript'>window.location.href='http://".$_SERVER['SERVER_NAME'].PARTNER_ROOT."/partner-order-list.php'</script>";
        }
    }
    elseif($_GET['trade_status'] == 'TRADE_CLOSED'){
//        // 判断交易单号与金额能否对上
        $data = array("status"=>2);
        $db->where("order_no", $out_trade_no);
        $db->update("xt_code_order", $data);
        echo "交易关闭，跳转中...<br />"
            ."<script type='text/javascript'>window.location.href='http://".$_SERVER['SERVER_NAME'].PARTNER_ROOT."/partner-order-list.php'</script>";
    }

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    //如要调试，请看alipay_notify.php页面的verifyReturn函数
    echo "验证失败";
}
?>
        <title>支付宝即时到账交易接口</title>
	</head>
    <body>
    </body>
</html>