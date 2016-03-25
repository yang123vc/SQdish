 <?php
/*
*created by sublime
*Author:Don Fisher
*Dtate:2016/2/16
*
*/
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	include_once dirname(__FILE__).'/../util/param.php';
	include_once dirname(__FILE__).'/../util/param.php';
	//引入支付配置文件
	require_once(dirname(__FILE__) . '/WapPay/server/init.php');	
	$db = new DB();
	date_default_timezone_set('PRC');
	//接收webhooks

	$event = json_decode(file_get_contents("php://input"),true);

	// 对异步通知做处理
	if (!isset($event['type'])) {
	    header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
	    exit("fail");
	}
	switch ($event['type']) {
	    case "charge.succeeded":
	        // 开发者在此处加入对支付异步通知的处理代码
	    	//订单号
	    	$out_trade_no = $event['data']['object']['order_no'];
	    	//流水号
	    	$trade_no = $event['data']['object']['transaction_no'];
	    	//交易状态 
	    	$trade_status = ($event['data']['object']['paid'])?1:0;
	    	//交易金额
	    	$total_fee = $event['data']['object']['amount'];

	    	//更改交易状态
	    	$orderInfo = $db->get_one("select *from f_order where ordercode= '".$out_trade_no."'");
	    	$result = $db->query("update f_order set status = 1 where id = ".$orderInfo['id']."");
       		$db->query("update f_order_detail set dish_status = 1 where oid = ".$orderInfo['id']."");

       		//写入交易表
       		$pay_record['order_id'] = $orderInfo['id'];
	        	$pay_record['out_trade_no'] =  $out_trade_no;
	       	$pay_record['trade_no'] = $trade_no;
	        	$pay_record['create_time'] = date('Y-m-d H:i:s',time());
	        	$pay_record['fee']  = $total_fee;
	        	$pay_record['status'] = $trade_status;
	        	$db->insert('f_pay_record',$pay_record);

	        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
	        break;
	    case "refund.succeeded":
	        // 开发者在此处加入对退款异步通知的处理代码
	        header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
	        break;
	    default:
	        header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
	        break;
	}
?>	