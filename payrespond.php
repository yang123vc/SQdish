<?php
/**
 * Created by PhpStorm.
 * User: guoy
 * Date: 2016/1/13
 * Time: 13:15
 */

ini_set("display_errors",1);
$isw=0;//α��̬����  1���� 0�ر�
date_default_timezone_set('Asia/Shanghai');
include_once dirname(__FILE__).'/data/db/DB.php';
require_once dirname(__FILE__).'/inc/alipay/alipay.config.php';
require_once dirname(__FILE__).'/inc/alipay/lib/alipay_notify.class.php';
$db = new DB();

//����ó�֪ͨ��֤���
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//��֤�ɹ�
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //������������̻���ҵ���߼������


    //�������������ҵ���߼�����д�������´�������ο�������

    //��ȡ֧������֪ͨ���ز������ɲο������ĵ��з������첽֪ͨ�����б�

    //�̻�������

    $out_trade_no = $_POST['out_trade_no'];

    //֧�������׺�

    $trade_no = $_POST['trade_no'];

    //����״̬
    $trade_status = $_POST['trade_status'];

    $total_fee = $_POST['total_fee']; //���׽��


    $orderInfo = $db->get_one("select *from f_order where ordercode= '".$out_trade_no."'");



    if($_POST['trade_status'] == 'TRADE_FINISHED') {
        //�жϸñʶ����Ƿ����̻���վ���Ѿ���������
        //���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
        //������ж�����ʱ��total_fee��seller_id��֪ͨʱ��ȡ��total_fee��seller_idΪһ�µ�
        //���������������ִ���̻���ҵ�����

        //ע�⣺
        //�˿����ڳ������˿����޺��������¿��˿��֧����ϵͳ���͸ý���״̬֪ͨ

        //�����ã�д�ı�������¼������������Ƿ�����
        //logResult("����д����Ҫ���ԵĴ������ֵ�����������еĽ����¼");
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
        //�жϸñʶ����Ƿ����̻���վ���Ѿ���������
        //���û�������������ݶ����ţ�out_trade_no�����̻���վ�Ķ���ϵͳ�в鵽�ñʶ�������ϸ����ִ���̻���ҵ�����
        //������ж�����ʱ��total_fee��seller_id��֪ͨʱ��ȡ��total_fee��seller_idΪһ�µ�
        //���������������ִ���̻���ҵ�����

        //ע�⣺
        //������ɺ�֧����ϵͳ���͸ý���״̬֪ͨ

        //�����ã�д�ı�������¼������������Ƿ�����
        //logResult("����д����Ҫ���ԵĴ������ֵ�����������еĽ����¼");
    }

    //�������������ҵ���߼�����д�������ϴ�������ο�������
    $result = $db->query("update f_order set status = 1 where id = ".$orderInfo['id']."");

    $pay_record['order_id'] = $orderInfo['id'];
    $pay_record['out_trade_no'] =  $out_trade_no;
    $pay_record['trade_no'] = $trade_no;
    $pay_record['create_time'] = date('Y-m-d H:i:s',time());
    $pay_record['fee']  = $total_fee;
    $db->insert('f_pay_record',$pay_record);

    echo "success";		//�벻Ҫ�޸Ļ�ɾ��

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //��֤ʧ��
    echo "fail";

    //�����ã�д�ı�������¼������������Ƿ�����
    //logResult("����д����Ҫ���ԵĴ������ֵ�����������еĽ����¼");
}