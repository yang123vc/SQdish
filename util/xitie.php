<?php
	//步骤检验
	function checkStep($xitie,$step=0){	
		if(!$xitie){
			header("Location:http://".$_SERVER['SERVER_NAME']."/login/login.html");
			exit();
		}else if($xitie->getStep() == 1 && $step != 1){
			header("Location:http://".$_SERVER['SERVER_NAME']."/member/step_list.php");
			exit();
		}else if($xitie->getStep() == 2 && $step != 2){
			header("Location:http://".$_SERVER['SERVER_NAME']."/member/step_list.php");
			exit();
		}else if($xitie->getStep() == 3 && $step != 3){
			header("Location:http://".$_SERVER['SERVER_NAME']."/member/step_list.php");
			exit();
		}else if($xitie->getStep() == 4 && $step != 4){
			header("Location:http://".$_SERVER['SERVER_NAME']."/member/step_list.php");
			exit();
		}else{
			//header("Location:http://".$_SERVER['SERVER_NAME']."/member/choose.php");
			header("Location:http://".$_SERVER['SERVER_NAME']."/a/index/");
			exit();
		}
	}
	
	//是否必须付费检验
	function checkActivable($xitie){
		if(isset($xitie['activable']) && $xitie['activable'] == 0 && $xitie['isBuy'] == 0){
			return -1;
		}else{
			return 0;
		}
		
		if(isset($xitie['free_trial']) && $xitie['free_trial'] == 0 && $xitie['isBuy'] == 0){
			return -2;
		}
	}
	
	function checkXitie($xitie){
		if(!$xitie){
			header("Location:http://".$_SERVER['SERVER_NAME']."/login/login.html");
			exit();
		}
	}
	
	//封装xitie
	function constructXitie($result,$xitie){
		$result->setId($xitie['id']);
		$result->setModuleId($xitie['moduleId']);
		$result->setUid($xitie['uid']);
		$result->setPath($xitie['path']);
		$result->setCreateDate($xitie['createDate']);
		$result->setPicPath($xitie['picPath']);
		$result->setMusicId($xitie['musicId']);
		$result->setCoverPath($xitie['coverPath']);
		$result->setHeadPic($xitie['headPic']);
		$result->setQRCodePic($xitie['QRCodePic']);
		$result->setIsMusic($xitie['isMusic']);
		$result->setMusicPath($xitie['musicPath']);
		$result->setGroom($xitie['groom']);
		$result->setBride($xitie['bride']);
		$result->setTips($xitie['tips']);
		$result->setPhone($xitie['phone']);
		$result->setWeddingTime($xitie['weddingTime']);
		$result->setAddress($xitie['address']);
		$result->setLPAddress($xitie['LPAddress']);
		$result->setProvince($xitie['province']);
		$result->setState($xitie['state']);
		$result->setType($xitie['type']);
		$result->setStep($xitie['step']);
		$result->setModulePic($xitie['modulePic']);
		$result->setIsBuy($xitie['isBuy']);
		$result->setClick($xitie['click']);
		$result->setTitle($xitie['title']);
		$result->setMusicAuto($xitie['musicAuto']);
		$result->setAnotherPhone($xitie['anotherPhone']);
		$result->setAnotherPhoneTips($xitie['anotherPhoneTips']);
		$result->setPhoneTips($xitie['phoneTips']);
		$result->setBaby($xitie['baby']);
		$result->setBabyType($xitie['babyType']);
		$result->setStopFlag($xitie['stopFlag']);
		$result->setVideo($xitie['video']);
		$result->setVideoFlag($xitie['videoFlag']);
		$result->setLunarFlag($xitie['lunarFlag']);
		$result->setModuleIdWeb($xitie['moduleIdWeb']);
		$result->setPathWeb($xitie['pathWeb']);
		$result->setTop($xitie['top']);
		$result->setSay($xitie['say']);
		
		//参数传递
		$_SESSION[XITIE] = $result;
		$_SESSION[MID] = $xitie['moduleId'];
		$_SESSION[MUID] = $xitie['id'];
		return $result;
	}
	
	//查找对应喜帖
	function findXitie($db){
		//获取登录用户
		if(isset($_SESSION[USERSESSION]))
			$userinfo = $_SESSION[USERSESSION];
		else
			return;
		
		if($userinfo){
			$uid = $userinfo->getId();
			$username = $userinfo->getUsername();
		}else
			return;

        if($db instanceof MysqliDb){
            $db->where("uid", $uid);
            $db->join("xt_module m", "mu.moduleId = m.id", "LEFT");
            $xitie = $db->getOne("xt_module_user mu", "mu.*,m.modulePic");
        } else{
            $query = "select xmu.*,xm.modulePic,xm.activable,xm.free_trial from xt_module_user xmu left join xt_module xm on xmu.moduleId = xm.id where uid = $uid";
            $xitie = $db->get_one($query);
        }
		
		$xitie['activableFlag'] = checkActivable($xitie);

		return $xitie;
	}
	
	//发送请求返回json,有错没有使用
	function do_post_request($url, $data){
		$params = array('http' => array(
					   'method' => 'POST',
					   'content' => $data
				  ));
		$pstr = stream_context_create($params);
		$fp = fopen($url, 'rb', false, $pstr);
		$response = stream_get_contents($fp);
		return $response;
	}
	
	//获得start,end之间的字符串
	function get_between($input, $start, $end) {
		$substr = substr($input, strlen($start)+strpos($input, $start),
		(strlen($input) - strpos($input, $end))*(-1));
		return $substr;
	}
	
	//判断是否为手机移动终端 
	function is_mobile_request(){  
		$_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
		$mobile_browser = '0';  
		if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipod|android)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
			$mobile_browser++;  
		if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
			$mobile_browser++;  
		if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
			$mobile_browser++;  
		if(isset($_SERVER['HTTP_PROFILE']))  
			$mobile_browser++;  
		$mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
		$mobile_agents = array(  
			'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
			'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
			'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
			'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
			'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
			'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
			'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
			'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
			'wapr','webc','winw','winw','xda','xda-'
		);  
		if(in_array($mobile_ua, $mobile_agents))  
			$mobile_browser++;  
		if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
			$mobile_browser++;  
		// Pre-final check to reset everything if the user is on Windows  
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
			$mobile_browser=0;  
		// But WP7 is also Windows, with a slightly different characteristic  
		if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
			$mobile_browser++;  
			
		if($mobile_browser>0){  
			return true;  
		}else{
			return false;
		}
	}
	
	function toreplace($str,$find){//$str是你需要操作的字符串,$find是你指定的字符串
		/*if(strpos($str,$find)===false){ 
			 ehco '对不起字符串'.$str.'中不包含字符串'.$find;
			 exit;
		}   
		
		$a=explode($find,$str);
		return $a[count($a)-1].$find;*/
	}
	
	function getip_out(){ 
		$ip=false; 
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){ 
			$ip = $_SERVER["HTTP_CLIENT_IP"]; 
		} 
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']); 
			if ($ip) { array_unshift($ips, $ip); $ip = FALSE; } 
				for ($i = 0; $i < count($ips); $i++) { 
					if (!eregi ("^(10│172.16│192.168).", $ips[$i])) { 
					$ip = $ips[$i]; 
					break; 
				} 
			} 
		} 
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']); 
	} 
	

	/** * 用DES算法加密/解密字符串 * *
	  @param string $string 待加密的字符串 
	  @param string $key 密匙，和管理后台需保持一致
	  @return string 返回经过加密/解密的字符串
	*/
	// 加密，注意，加密前需要把数组转换为json格式的字符串 
	function des_encrypt($string, $key) {
		$size = mcrypt_get_block_size('des', 'ecb');
		$string = mb_convert_encoding($string, 'GBK', 'UTF-8');
		$pad = $size - (strlen($string) % $size);
		$string = $string . str_repeat(chr($pad), $pad);
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		@mcrypt_generic_init($td, $key, $iv);
		$data = mcrypt_generic($td, $string);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$data = base64_encode($data);
		return $data;
	}

	// 解密，解密后返回的是json格式的字符串
	function des_decrypt($string, $key) {
		$string = base64_decode($string);
		$td = mcrypt_module_open('des', '', 'ecb', '');
		$iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
		@mcrypt_generic_init($td, $key, $iv);
		$decrypted = mdecrypt_generic($td, $string);
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		$pad = ord($decrypted{strlen($decrypted) - 1});
		if($pad > strlen($decrypted)) {
			return false;
		}
		if(strspn($decrypted, chr($pad), strlen($decrypted) - $pad) != $pad) {
			return false;
		}
		$result = substr($decrypted, 0, -1 * $pad);
		$result = mb_convert_encoding($result, 'UTF-8', 'GBK');
		return $result;
	}
?>