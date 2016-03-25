<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/14
 * Time: 10:58
 */

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

include_once dirname(__FILE__).'/data/db/DB.php';
require_once dirname(__FILE__).'/inc/wxpay/lib/WxPay.Api.php';
require_once dirname(__FILE__).'/inc/wxpay/lib/WxPay.Notify.php';
require_once dirname(__FILE__).'/inc/wxpay/log.php';

$wxdir = dirname(__FILE__).'/inc/wxpay/';
//��ʼ����־
$logHandler= new CLogFileHandler( $wxdir."logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

$db = new DB();


$xml = $GLOBALS["HTTP_RAW_POST_DATA"];
$array = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

if($array['result_code']=='SUCCESS' && $array['result_code'] == 'SUCCESS')
{
    $out_trade_no = $array['out_trade_no'];
    $total_fee = $array['total_fee'];
    $transaction_id  = $array['transaction_id'];
    $orderInfo = $db->get_one("select *from f_order where ordercode= '".$out_trade_no."'");
    $result = $db->query("update f_order set status = 1 where id = ".$orderInfo['id']."");
    $pay_record['order_id'] = $orderInfo['id'];
    $pay_record['out_trade_no'] =  $out_trade_no;
    $pay_record['payment'] =  1;
    $pay_record['trade_no'] = $transaction_id;
    $pay_record['create_time'] = date('Y-m-d H:i:s',time());
    $pay_record['fee']  = $total_fee;
    $db->insert('f_pay_record',$pay_record);

}






class PayNotifyCallBack extends WxPayNotify
{
    //��ѯ����
    public function Queryorder($transaction_id)
    {



        $input = new WxPayOrderQuery();
        $input->SetTransaction_id($transaction_id);
        $result = WxPayApi::orderQuery($input);
        Log::DEBUG("query:" . json_encode($result));
        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS")
        {
            return true;
        }
        return false;
    }

    //��д�ص�������
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();

        if(!array_key_exists("transaction_id", $data)){
            $msg = "�����������ȷ";
            return false;
        }
        //��ѯ�������ж϶�����ʵ��
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "������ѯʧ��";
            return false;
        }
        return true;
    }
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);