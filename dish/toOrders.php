  <?php
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../dish/function.php';
	include_once dirname(__FILE__).'/../util/param.php';
	include_once dirname(__FILE__).'/../util/param.php';
	//引入支付配置文件
	require_once(dirname(__FILE__) . '/WapPay/server/init.php');
	session_start();
	
	date_default_timezone_set('PRC');
	
	$db = new DB();



	


	//跳转支付接口
	 $input_data = json_decode(file_get_contents('php://input'), true);
	if (empty($input_data['channel']) || empty($input_data['amount'])) {
	    echo 'channel or amount is empty';
	    exit();
	}
	//基本参数
	$channel = strtolower($input_data['channel']);
	$amounts = $input_data['amount'];
	$orderNo = time().rand(1000,9999);
	//$orderNo = $input_data['order_no'];
	//会员id，未登录的话默认为0
	$mid = isset($_SESSION['user_info']['id'])?$_SESSION['user_info']['id']:0;
	//如果是微信支付，获取支付open_id
	if ($channel=='wx_pub') {
		//接获取open_id
			$sql = "select open_id from f_member where id='$mid'";
			$open_id = $db->get_a($sql);	
	}
	$id = $input_data['id'];
	$did = $input_data['did'];

	$nameCh = $input_data['nameCh'];
	$nameThai = $input_data['nameThai'];
	$price = $input_data['price'];
	$opName = $input_data['opName'];
	$opNameThai = $input_data['opNameThai'];
	$num = $input_data['num'];

	$exchangeRate = getValue('exchangeRate');
	
	$statisticalCode = getValue('statisticalCode');

	$date = date('y-m-d H:i',time());
	$detail = "";
	$totalPrice = 0.00;

	for($i=0;$i<count($did);$i++){
		if($opName[$i]){
			$opN = "--$opName[$i]($opNameThai[$i])";
		}else {
			$opN = "";
		}
		
		$detail .= "$nameCh[$i]($nameThai[$i])$opN * $num[$i]--- $price[$i]<br>";
		$totalPrice += $price[$i]*$num[$i];
		
		$oNum = $num[$i];
		$oDid = $did[$i];
		$sql = "update f_dish set used = used+$oNum where id = $did[$i]";
		$db->query($sql);
	}
		
	$temp = $db->get_one("select * from f_statistics where detail = '$detail' and price = $totalPrice and sid = $id");
	
	if(!$temp){
		$sql = "insert into f_statistics(`date`,detail,price,sid) values('$date','$detail',$totalPrice,$id)";
		$db->query($sql);
	}

	//订单同时写入f_order

	$orderArray = array();
	$orderArray['mid'] = $mid;
	//订单时间
	$orderArray['time'] = time();
	//订单状态
	$orderArray['status'] = 0;
	//订单号
	$orderArray['ordercode'] = $orderNo;
	//商家id
	$orderArray['sid'] = $id;
	//支付方式，微信或者支付宝
	if ($channel=='alipay_wap') {
		$orderArray['pay_type']  = 0;
	}else if ($channel=='wx_pub') {
		$orderArray['pay_type']  = 1;
	}
	//订单价格
	$orderArray['all_cost'] = $amount =  number_format($totalPrice*$exchangeRate,2);
	//订单数量
	$orderArray['count'] = array_sum($num);
	//开启事务
	
	//mysql_query('START TRANSACTION');
	$shop_name = $db->get_a("select sellerName from f_seller where id='$id'");
	$shop_name =substr($shop_name, 0,32);
	//写入f_order表
	$result = $db->insert('f_order',$orderArray);
	$order_id = $db->insert_id();
		
		for($i=0;$i<count($did);$i++){
			$detail = array();
			$detail['oid'] = $order_id;//订单id
			$detail['did'] = $did[$i];//菜品id
			$detail['goods_price'] = number_format($price[$i]*$exchangeRate,2);//菜品价格
			$detail['goods_count'] = $num[$i];//菜品数量
			
			$detail_rs = $db->insert('f_order_detail',$detail);
		}

	//清空购物车
	if(isset($_SESSION["cart"])){
		unset($_SESSION["cart"]);
	}
			








	//$extra 在使用某些渠道的时候，需要填入相应的参数，其它渠道则是 array() .具体见以下代码或者官网中的文档。其他渠道时可以传空值也可以不传。
	$extra = array();
	switch ($channel) {
	    case 'alipay_wap':
	        $extra = array(
	            'success_url' => 'http://www.sqdish.com/wap_success.php?no='.$orderNo,
	            'cancel_url' => 'http://www.sqdish.com'
	        );
	        break;
	    case 'upmp_wap':
	        $extra = array(
	            'result_url' => 'http://www.yourdomain.com/result?code='
	        );
	        break;
	    case 'bfb_wap':
	        $extra = array(
	            'result_url' => 'http://www.yourdomain.com/result?code=',
	            'bfb_login' => true
	        );
	        break;
	    case 'upacp_wap':
	        $extra = array(
	            'result_url' => 'http://www.yourdomain.com/result'
	        );
	        break;
	    case 'wx_pub':
	        $extra = array(
	            'open_id' =>$open_id
	        );
	        break;
	    case 'wx_pub_qr':
	        $extra = array(
	            'product_id' => 'Productid'
	        );
	        break;
	    case 'yeepay_wap':
	        $extra = array(
	            'product_category' => '1',
	            'identity_id'=> 'your identity_id',
	            'identity_type' => 1,
	            'terminal_type' => 1,
	            'terminal_id'=>'your terminal_id',
	            'user_ua'=>'your user_ua',
	            'result_url'=>'http://www.yourdomain.com/result'
	        );
	        break;
	    case 'jdpay_wap':
	        $extra = array(
	            'success_url' => 'http://www.yourdomain.com',
	            'fail_url'=> 'http://www.yourdomain.com',
	            'token' => 'dsafadsfasdfadsjuyhfnhujkijunhaf'
	        );
	        break;
	}

	\Pingpp\Pingpp::setApiKey('sk_live_TWzTuLPmvbfTzvXfX1TOy1W1');
	try {
	    $ch = \Pingpp\Charge::create(
	        array(
	            'subject'   => $shop_name,
	            'body'      => 'Your Body',
	            'amount'    => $amount*100,
	            'order_no'  => $orderNo,
	            'currency'  => 'cny',
	            'extra'     => $extra,
	            'channel'   => $channel,
	            'client_ip' => $_SERVER['REMOTE_ADDR'],
	            'app'       => array('id' => 'app_zHGOmD9WDGaPD8Wv')
	        )
	    );
	    echo $ch;
	} catch (\Pingpp\Error\Base $e) {
	    header('Status: ' . $e->getHttpStatus());
	    echo($e->getHttpBody());
	}

?>