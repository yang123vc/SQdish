<?php 
	//header("Location:http://".$_SERVER['SERVER_NAME']);
	//exit();

    $db_config["hostname"] = "127.0.0.1"; //服务器地址
    $db_config["username"] = "root"; //数据库用户名
    $db_config["password"] = "123456"; //数据库密码
    $db_config["database"] = "caipin"; //数据库名称
    $db_config["charset"] = "utf8";//数据库编码
    $db_config["pconnect"] = 1;//开启持久连接
    $db_config["log"] = 0;//开启日志
    $db_config["logfilepath"] = dirname(__FILE__).'/../../data/dbLog/';//日志路径
