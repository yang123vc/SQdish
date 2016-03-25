<?php 
include_once 'session_class.php';
include_once 'json_class.php';
include_once 'cookie_class.php';
/**
 * @class Cart
 * @brief 购物车类库
 */
class Cart
{

	/*
	*  构造多店铺  多商品购物车计算方案
	* shopid  goodsid  shuliang
	*/
	/*购物车cookie存储结构
	* array [goods]=>array(商品主键=>数量) , [product]=>array( 货品主键=>数量 )  [shopinfo] =>array(店铺id=>商品id)
	* array[shopid]=>array(gid=>数量);
	*/
	private $cartStruct = array();

	/*购物车返还结构
	* [id]   :array  商品id值;
	* [count]:int    商品数量;
	* [info] :array  商品信息 [goods]=>array( ['id']=>商品ID , ['data'] => array( [商品ID]=>array ( [sell_price]价格, [count]购物车中此商品的数量 ,[type]类型goods,product ,[goods_id]商品ID值 ) ) ) , [product]=>array( 同上 ) , [count]购物车商品和货品数量 , [sum]商品和货品总额 ;
	* [sum]  :int    商品总价格;
	*/
	private $cartExeStruct = array('list'=>array(),'count' => 0,'sum' => 0);

	//购物车名字前缀
	private $cartName    = 'shoppingcart';

	//购物车中最多容纳的数量
	private $maxCount    = 100;

	//错误信息
	private $error       = '';
	private  $allowed   = 3;//是否允许多店铺添加到购物车 1: 购物车仅 存一种商品   2 : 购物车可同存商城    3:购物不限制商品数量


	//购物车的存储方式
	private $saveType    = 'cookie';

	private $dbhost; //数据库主机
	private $dbuser; //数据库用户名
	private $dbpassword; //数据库用户名密码
	private $dbname; //数据库名
	private $dbcharset; //数据库编码，GBK,UTF8,gb2312
	private $conn; //数据库连接标识;
	private $pconn; //是否永久连接

	//构造函数
	function __construct()
	{

		require(dirname(__FILE__).'/../data/db/config.db.php');
		$this->dbhost = $db_config["hostname"] ;
		$this->dbuser = $db_config["username"];
		$this->dbpassword = $db_config["password"];
		$this->dbname = $db_config["database"];
		$this->conn = 'conn';
		$this->pconn = 'false';
		$this->dbcharset = 'UTF8';
		$this->connect();

		$cartInfo = $this->getMyCartStruct();
		$this->setMyCart($cartInfo);
	}

	private function getMyCartStruct()
	{
		$cartName  = $this->getCartName();
		if($this->saveType == 'session')
		{
			$cartValue = ISession::get($cartName);
		}
		else
		{
			$cartValue = ICookie::get($cartName);
		}

		if($cartValue == null)
		{
			return $this->cartStruct;
		}
		else
		{
			$cartValue = JSON::decode(str_replace(array('&','$'),array('"',','),$cartValue));
			return $cartValue;
		}
	}
	//写入购物车
	public function setMyCart($goodsInfo)
	{
		$goodsInfo = str_replace(array('"',','),array('&','$'),JSON::encode($goodsInfo));
		$cartName = $this->getCartName();
		if($this->saveType == 'session')
		{
			ISession::set($cartName,$goodsInfo);
		}
		else
		{
			ICookie::set($cartName,$goodsInfo,'7200');
		}
		return true;
	}
	//获取当前购物车信息
	public function getMyCart()
	{


		$cartName  = $this->getCartName();

		if($this->saveType == 'session')
		{
			$cartValue = ISession::get($cartName);
		}
		else
		{
			$cartValue = ICookie::get($cartName);
		}

		if($cartValue == null)
		{
			return $this->cartExeStruct;
		}
		else
		{
			$cartValue = JSON::decode(str_replace(array('&','$'),array('"',','),$cartValue));




			if(is_array($cartValue))
			{
				return $this->cartFormat($cartValue);
			}
			else
			{
				return $this->cartExeStruct;
			}
		}
	}

	/**
	 * @brief 将商品或者货品加入购物车
	 * @param $gid  商品或者货品ID值
	 * @param $num  购买数量
	 * @param $shopid 店铺ID;
	 */
	public function add($gid, $num = 1 ,$shopid)
	{
		//购物车中已经存在此商品
		$cartInfo = $this->getMyCartStruct();

		if($this->getCartSort($cartInfo) >= $this->maxCount)
		{
			$this->error = '加入购物车失败,购物车中最多只能容纳'.$this->maxCount.'种商品';
			return false;
		}
		else
		{

			$cartInfo = $this->getUpdateCartData($cartInfo,$gid,$num,$shopid);

			if($cartInfo === false)
			{
				return false;
			}
			else
			{

				return $this->setMyCart($cartInfo);
			}
		}
	}


	//计算商品的种类
	private function getCartSort($mycart)
	{
		$sumSort   = 0;
		$sortArray = $mycart;

		foreach($sortArray as $key=>$sort)
		{
			$sumSort += count($sort);
		}
		return $sumSort;
	}

	//获取新加入购物车的数据
	public function getUpdateCartData($cartInfo,$gid,$num,$shopid)
	{
		$gid = intval($gid);
		$num = intval($num);
		$shopid = intval($shopid);
		//获取基本的商品数据
		$goodsRow = $this->getGoodInfo($gid);
		if($goodsRow)
		{
			if($goodsRow['shopid'] != $shopid)
			{
				$this->error='商品和店铺不关联';
				return false;
			}elseif(isset($cartInfo[$shopid][$gid]))
			{//购物车中已经存在此类商品

//				if($goodsRow['shuliang'] < $cartInfo[$shopid][$gid] + $num)
//				{
//
//					$this->error = '该商品库存不足';
//					return false;
//				}
				$cartInfo[$shopid][$gid] += $num;
			}else{//购物车中不存在此类商品
//				if($goodsRow['shuliang'] < $num)
//				{
//					$this->error = '该商品库存不足';
//					return false;
//				}
				if($this->allowed   ==  1){
					if(count($cartInfo) > 0){
						if(!isset($cartInfo[$shopid])){

							$this->error = '添加购物车商品仅限1个店铺，请先清除购物车在进行添加';
							return false;
						}
					}

				}elseif($this->allowed ==  2){

					if(count($cartInfo) > 0){

						if(isset($cartInfo[0]) && count($cartInfo) > 1){
							if(!isset($cartInfo[$shopid])){
								$this->error = '添加购物车商品仅限1个店铺和商城商品，请先清除购物车在进行添加';
								return false;
							}
						}
						if(!isset($cartInfo[0]) && count($cartInfo) > 0){
							if(!isset($cartInfo[$shopid])){
								$this->error = '添加购物车商品仅限1个店铺和商城商品，请先清除购物车在进行添加';
								return false;
							}
						}

					}
				}

				$cartInfo[$shopid][$gid] = $num;
			}
			return $cartInfo;
		}
		else
		{
			$this->error = '该商品不存在';
			return false;
		}
	}




	//删除商品
	public function del($gid , $shopid)
	{
		$cartInfo = $this->getMyCartStruct();


		//删除商品数据
		if(isset($cartInfo[$shopid][$gid]))
		{
			unset($cartInfo[$shopid][$gid]);
			if(count($cartInfo[$shopid]) == 0){
				unset($cartInfo[$shopid]);
			}
			$this->setMyCart($cartInfo);
			return true;
		}
		else
		{
			$this->error = '购物车中没有此商品';
			return false;
		}
	}
	public function delshop($shopid){
		$cartInfo = $this->getMyCartStruct();


		//删除商品数据
		if(isset($cartInfo[$shopid]))
		{
			unset($cartInfo[$shopid]);
			$this->setMyCart($cartInfo);
			return true;
		}
		else
		{
			$this->error = '购物车中无此店铺商品';
			return false;
		}
	}
	//根据 $gid 获取商品信息
	private function getGoodInfo($gid)
	{

		$dataArray = array();
		$dataArray = $this->select_one("select id,dishNameCN as name,price as cost,pic as img,sid as shopid,used from  f_dish where id=".$gid);
		return $dataArray;
	}

	//获取当前购物车的结构化数据JSON


	//清空购物车
	public function clear()
	{
		$cartName = $this->getCartName();
		if($this->saveType == 'session')
		{
			ISession::clear($cartName);
		}
		else
		{
			ICookie::clear($cartName);
		}
	}




	/**
	 * @brief  把cookie的结构转化成为程序所用的数据结构
	 * @param  $cartValue 购物车cookie存储结构
	 * @return array : [goods]=>array( ['id']=>商品ID , ['data'] => array( [商品ID]=>array ([name]商品名称 , [list_img]图片地址 , [sell_price]价格, [count]购物车中此商品的数量 ,[type]类型goods,product , [goods_id]商品ID值 ) ) ) , [product]=>array( 同上 ) , [count]购物车商品和货品数量 , [sum]商品和货品总额 ;
	 */
	private function cartFormat($cartValue)
	{
		//初始化结果

		$result = $this->cartExeStruct;


		$goodsIdArray = array();
		$backdata = $result;
		if(count($cartValue) > 0)
		{
			$ids = array_keys($cartValue);


			$ids = join(',',$ids);

			$shopinfo= array();
			if(!empty($ids)){
				$shoplist = $this->getarr("select id,sellerName,QRCode  from f_seller  where id in (".$ids.")  ");

				foreach($shoplist as $key=>$value){
					$shopinfo[$value['id']] = $value;
				}

			}







			foreach($cartValue as $key=>$value){
				if($key ==  0){
					$backdata['list'][$key]['shopinfo']= array('id'=>0,'shopname'=>'商城');
				}else{
					$backdata['list'][$key]['shopinfo'] = $shopinfo[$key];
				}



				foreach($value as $k=>$v){
					$backdata['list'][$key]['data'][$k]['count'] = $v;
					$backdata['list'][$key]['data'][$k]['id'] = $k;
					$backdata['list'][$key]['count'] = 0;
					$backdata['list'][$key]['sum'] = 0;
					$backdata['list'][$key]['bagcost']  = 0;
					$goodsIdArray[] = $k;
					$backdata['count'] +=  $v;
				}
			}
		}


		if($goodsIdArray)
		{
			$goodsArray = array();
			$goodsData = $this->getarr(" select id,dishNameCN as name,price as cost,pic as img,sid as shopid,used from f_dish  where id in (".join(",",$goodsIdArray).")  order by sid asc");
			foreach($goodsData as $goodsVal)
			{
				$goodsArray[$goodsVal['id']] = $goodsVal;
			}

			$tempshopids = array();
			foreach($goodsData as $key => $val)
			{

				$backdata['list'][$val['shopid']]['data'][$val['id']]['img']   = $val['img'];
				$backdata['list'][$val['shopid']]['data'][$val['id']]['name']       = $val['name'];
				$backdata['list'][$val['shopid']]['data'][$val['id']]['cost'] = $val['cost'];
				$backdata['list'][$val['shopid']]['data'][$val['id']]['shopid'] = $val['shopid'];
				//$backdata['list'][$val['shopid']]['data'][$val['id']]['store_num'] = $val['shuliang'];
				$backdata['list'][$val['shopid']]['data'][$val['id']]['sellcount'] = $val['sellcount'];
				$backdata['list'][$val['shopid']]['data'][$val['id']]['bagcost'] = $val['bagcost'];
				$backdata['list'][$val['shopid']]['data'][$val['id']]['typeid'] = $val['typeid'];
				$backdata['list'][$val['shopid']]['sum'] += $val['cost'] * $backdata['list'][$val['shopid']]['data'][$val['id']]['count'];
				$backdata['list'][$val['shopid']]['count'] += $backdata['list'][$val['shopid']]['data'][$val['id']]['count'];
				$backdata['list'][$val['shopid']]['bagcost'] += $backdata['list'][$val['shopid']]['data'][$val['id']]['count'] * $val['bagcost'];
				$backdata['sum']   += $val['cost'] * $backdata['list'][$val['shopid']]['data'][$val['id']]['count'];
			}
		}

		return $backdata;
	}

	//[私有]获取购物车名字
	private function getCartName()
	{
		return $this->cartName;
	}

	//获取错误信息
	public function getError()
	{
		return $this->error;
	}

	//连接数据库
	private function connect(){
		if($this->pconn){
			$this->conn=@mysql_pconnect($this->dbhost,$this->dbuser,$this->dbpassword) or die($this->error());
		}else{
			$this->conn=@mysql_connect($this->dbhost,$this->dbuser,$this->dbpassword) or die($this->error());
		}
		$version = @mysql_get_server_info();

		if($version > '4.1' && $this->dbcharset) {
			@mysql_query("SET NAMES $this->dbcharset") or die ($this->error());
		}
		if($version > '5.0') {
			@mysql_query("SET sql_mode=''") or die ($this->error());
		}


		@mysql_select_db($this->dbname, $this->conn) or die ($this->error());

	}

	//执行一条SQL语句
	public function query($sql) {
		$query = @mysql_query($sql,$this->conn) or die($this->error($sql));
		return $query;
	}


	//信息生成数组(双方式)
	public function arr($query){
		return mysql_fetch_array($query);
	}

	//获取一条数据。
	public function select_one($sql){
		$query=$this->query($sql);
		return $this->assoc($query);
	}

	//信息生成数组(字段名方式)
	public function assoc($query) {
		return mysql_fetch_assoc($query);
	}

	public function getarr($sql){
		$getarr = array();
		$query = $this->query($sql);
		while($rs=$this->assoc($query)){
			$getarr[] = $rs;
		}
		return $getarr;
	}
	//错误信息提示
	public function error($sql=""){

		$error = "Mysql_error：".mysql_error()."(".mysql_errno().")";
		$error .= $sql == "" ? "" : "Sql：'{$sql}'";
		//logwrite($error);
		return false;
	}


}
?>