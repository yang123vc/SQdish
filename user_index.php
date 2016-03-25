<?php
ini_set("display_errors",0);
$isw = 0; // 伪静态配置 1开启 0关闭
date_default_timezone_set ( 'Asia/Shanghai' );
include_once dirname ( __FILE__ ) . '/data/db/DB.php';
include_once dirname ( __FILE__ ) . '/inc/page.class.php';
include_once dirname ( __FILE__ ) . '/inc/global.inc.php';
	$db = new DB ();
// 判断登录后信息
$uid = get_user_info();
	if($uid) {
			$user_rs = $user_info = $db -> get_one("select * from f_member where id=".$uid);
	
	// -----------------获取我的收藏信息 列出最新收藏的商户start-------------------//
	
	$m_sql = "select b.id,b.sellerName,b.pic from f_favourite as a,f_seller as b where a.fid = b.id and a.cat = 1 and a.status = 0 and a.mid = " . $uid . " order by a.time desc limit 4";
	
	
	$merchant_rs = $db->get_all($m_sql);
	foreach ( $merchant_rs as &$rs ) {
		$rs ['sellerName_cut'] = CutStr ( $rs ['sellerName'], 6 );
	}
	// -----------------获取我的收藏信息 列出最新收藏的商户end--------------------//
	

	// -----------------获取当前登录用户最近下的订单start-----------------------//
	/*$o_sql = "select s.id,s.sellerName,s.pic,r.time,r.ordercode,r.status,sum(h.price) as price from f_order as r,f_order_detail as l,f_dish as h,f_seller as s where r.id = l.oid and l.did = h.id and h.sid = s.id and r.mid = ". $mid . " group by l.oid order by time desc";*/
	$o_sql = "SELECT id,sid,ordercode,all_cost,time,status FROM f_order WHERE mid ='$uid' AND is_on=1 ORDER BY id DESC LIMIT 4";
	$orders = $db->get_all ( $o_sql );
	foreach ($orders as $v) {
		$sql = "select sellerName,pic from f_seller where id='$v[sid]'";
		$v['seller'] = $db->get_one($sql);
		$order_rs[] = $v;
	}
	// -----------------获取当前登录用户最近下的订单end-------------------------//
	

	$agent = $_SERVER['HTTP_USER_AGENT'];  
	
	if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")) {

	include_once dirname(__FILE__)."/templates/wap/member/index.html";

}else {	

	include_once dirname ( __FILE__ ) . "/templates/user_index.html";

};
	exit;
} else {
	$jump_url = 'http://'.$_SERVER['HTTP_HOST'].'/login.php';
	header("Location:".$jump_url);	
}
?>