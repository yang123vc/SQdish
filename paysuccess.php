<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/13
 * Time: 13:15
 */

include_once dirname(__FILE__).'/data/db/DB.php';
require_once dirname(__FILE__).'/inc/alipay/alipay.config.php';
require_once dirname(__FILE__).'/inc/alipay/lib/alipay_notify.class.php';
$db = new DB();
    //����ó�֪ͨ��֤���
    $alipayNotify = new AlipayNotify($alipay_config);
    $verify_result = $alipayNotify->verifyReturn();
    if($verify_result) {//��֤�ɹ�
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //������������̻���ҵ���߼��������

        //�������������ҵ���߼�����д�������´�������ο�������
        //��ȡ֧������֪ͨ���ز������ɲο������ĵ���ҳ����תͬ��֪ͨ�����б�

        //�̻�������

        $out_trade_no = $_GET['out_trade_no'];

        //֧�������׺�

        $trade_no = $_GET['trade_no'];

        //����״̬
        $trade_status = $_GET['trade_status'];

        $total_fee = $_GET['total_fee']; //���׽��

        $orderInfo = $db->get_one("select *from f_order where ordercode= '".$out_trade_no."'");


        if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
            //�жϸñʶ����Ƿ����̻���վ���Ѿ���������
            //���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
            //���������������ִ���̻���ҵ�����
        }
        else {
            echo "trade_status=".$_GET['trade_status'];
        }

        $result = $db->query("update f_order set status = 1 where id = ".$orderInfo['id']."");
        $db->query("update f_order_detail set dish_status = 1 where oid = ".$orderInfo['id']."");
        
        $pay_record['order_id'] = $orderInfo['id'];
        $pay_record['out_trade_no'] =  $out_trade_no;
        $pay_record['trade_no'] = $trade_no;
        $pay_record['create_time'] = date('Y-m-d H:i:s',time());
        $pay_record['fee']  = $total_fee;
        $db->insert('f_pay_record',$pay_record);
        include_once dirname(__FILE__)."/templates/member/paysuccess.html";

        //�������������ҵ���߼�����д�������ϴ�������ο�������

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    }
    else {

        header("Content-type:text/html;charset=GBK");
        echo "��֤ʧ��";
    }



?>


