<?php 


function wyc($body){
	$ini = ini_set("soap.wsdl_cache_enabled","0");
  $wsdl = 'http://www.naipan.com/NaipanPort?WSDL';		
  $content = $body;
  $param   = array(
      "arg0" => $content,
      "arg1" => "test@163.com",
      "arg2" => "ICQl3kdebh7zns97XVT9dLDBASR7pBrM2AAKbI7HpMw="
  );
  $client = new SoapClient($wsdl);
  $result = $client->getContent($param,1);
        
  return $result->return;
	// $body;
}
	
	
function mwb($body,$keyword){
	$maxnum=3;
	$nownum=0;
	if(is_array($keyword)){
		foreach($keyword as $k=>$v){
			
			if(strpos($body,$v['KeywordName'])){
				
			 $new="<a href='{$v[KeywordUrl]}'>{$v['KeywordName']}</a>";
				$pat="/<a (.*?)>{$v['KeywordName']}<\/a>/i";
				preg_match_all($pat,$body,$out);
				print_r($out);
				if(empty($out[0])){  
					$body=str_replace($v['KeywordName'],$new,$body);
					$nownum++;
				}				
			}
			
			if($nownum==$maxnum){break;}
		}
	}
	return $body;
	
}
function getIP() {
	if (getenv('HTTP_CLIENT_IP')) {
		$ip = getenv('HTTP_CLIENT_IP'); 
	}elseif (getenv('HTTP_X_FORWARDED_FOR')) { //获取客户端用代理服务器访问时的真实ip 地址
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	}elseif (getenv('HTTP_X_FORWARDED')) { 
		$ip = getenv('HTTP_X_FORWARDED');
	}elseif (getenv('HTTP_FORWARDED_FOR')) {
		$ip = getenv('HTTP_FORWARDED_FOR'); 
	}elseif (getenv('HTTP_FORWARDED')) {
		$ip = getenv('HTTP_FORWARDED');
	}else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;
}

function add_slashes ($an_array) {
  foreach ($an_array as $key => $value) {
    $new_array[$key] = addslashes($an_array[$key]);
  }
	return $new_array;
}


if ( ! function_exists('cn_substr_utf8'))
{
    function cn_substr_utf8($str, $length, $start=0)
    {
        if(strlen($str) < $start+1)
        {
            return '';
        }
        preg_match_all("/./su", $str, $ar);
        $str = '';
        $tstr = '';

        //为了兼容mysql4.1以下版本,与数据库varchar一致,这里使用按字节截取
        for($i=0; isset($ar[0][$i]); $i++)
        {
            if(strlen($tstr) < $start)
            {
                $tstr .= $ar[0][$i];
            }
            else
            {
                if(strlen($str) < $length + strlen($ar[0][$i]) )
                {
                    $str .= $ar[0][$i];
                }
                else
                {
                    break;
                }
            }
        }
        return $str;
    }
} 

function SpHtml2Text($str)
{
	$str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU","",$str);
	$alltext = "";
	$start = 1;
	for($i=0;$i<strlen($str);$i++)
	{
		if($start==0 && $str[$i]==">")
		{
			$start = 1;
		}
		else if($start==1)
		{
			if($str[$i]=="<")
			{
				$start = 0;
				$alltext .= " ";
			}
			else if(ord($str[$i])>31)
			{
				$alltext .= $str[$i];
			}
		}
	}
	$alltext = str_replace("　"," ",$alltext);
	$alltext = preg_replace("/&([^;&]*)(;|&)/","",$alltext);
	$alltext = preg_replace("/[ ]+/s"," ",$alltext);
	return $alltext;
}