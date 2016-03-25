<?php
//ini_set("display_errors",1);
//error_report(E_ALL);
	include_once dirname(__FILE__).'/../data/db/DB.php';
	include_once dirname(__FILE__).'/../inc/isLogin.config.php';
	//获取父栏目
	$db = new DB();
	$sql = "select * from f_email_cat";
	$catList = $db->get_all($sql);
	//递归格式化栏目
	/*
	function catsort($arr,$parent_id=0,$lev=1){
        		static $list =array();
        		foreach($arr as $v){
	            if($v['parent_id']==$parent_id){
	                $v['lev']=$lev;
	                $list[]=$v;
	                catsort($arr,$v['cat_id'],$lev+1);
	            }
	        }
	        return $list;
	 }
	 $catList = catsort($cat);*/
	// print_r($catList);exit;
	include_once dirname(__FILE__)."/templates/emailCatAdd.html";
?>