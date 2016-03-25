<?php 
	//header("Location:http://".$_SERVER['SERVER_NAME']);
	//exit();

    $db_config["hostname"] = "127.0.0.1"; //服务器地址
    $db_config["username"] = "sqdishsql"; //数据库用户名
    $db_config["password"] = "CAKKtJ2P39HBb36G"; //数据库密码
    $db_config["database"] = "sqdishsql"; //数据库名称
    $db_config["charset"] = "utf8";//数据库编码
    $db_config["pconnect"] = 1;//开启持久连接
    $db_config["log"] = 0;//开启日志
    $db_config["logfilepath"] = dirname(__FILE__).'/../../data/dbLog/';//日志路径
