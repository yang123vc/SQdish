<?php

    // 登录检查所需要的文件

    include_once dirname(__FILE__).'/../data/constant.php';

	

    session_start();

	

	if(isset($_SESSION['language'])){

		if($_SESSION['language'] == "en")

			include_once dirname(__FILE__).'/../inc/en.php';

		

		if($_SESSION['language'] == "thai")

			include_once dirname(__FILE__).'/../inc/thai.php';

		

		if($_SESSION['language'] == "cn")

			include_once dirname(__FILE__).'/../inc/cn.php';

	}else{

		$_SESSION['language'] = "en";

	}

	

	if(!isset($_SESSION[USERSESSION])){ // 没有登录的session信息

		$user = $_SESSION[USERSESSION];

		

		if($user['role'] != 2){

			if(isset($_SESSION['language'])){

				if($_SESSION['language'] == "en"){

					header("Location:http://".$_SERVER['SERVER_NAME']."/food_mg/templates/MakeSure_en.html");

					exit();

				}

				

				if($_SESSION['language'] == "thai"){

					header("Location:http://".$_SERVER['SERVER_NAME']."/food_mg/templates/MakeSure_thai.html");

					exit();

				}

				

				if($_SESSION['language'] == "cn"){

					header("Location:http://".$_SERVER['SERVER_NAME']."/food_mg/templates/MakeSure.html");

					exit();

				}

			}else{

				header("Location:http://".$_SERVER['SERVER_NAME']."/food_mg/templates/MakeSure_en.html");

				exit();

			}

		}

    }else{

		//echo $_SESSION[SID];

		//exit();

	}

?>