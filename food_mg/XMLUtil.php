<?php
	header("content-type:text/html; charset=utf-8");

	//路径方式：
	//$result = $obj->xpath('/Location/CountryRegion/State');
	
	//解析xml
	function xml2assoc($xml) {
		$tree = null;
		while($xml->read())
			switch ($xml->nodeType) {
				case XMLReader::END_ELEMENT: return $tree;
				case XMLReader::ELEMENT:
					$node = array('tag' => $xml->name, 'value' => $xml->isEmptyElement ? '' : xml2assoc($xml));
					if($xml->hasAttributes)
					while($xml->moveToNextAttribute())
						$node['attributes'][$xml->name] = $xml->value;
					$tree[] = $node;
					break;
				case XMLReader::TEXT:
				case XMLReader::CDATA:
					$tree .= $xml->value;
		}
		return $tree;
	}
	
	//遍历方式：
	//4层级联，获取xml所有结构（country,state,city,region）速度快，仅用于查询
	function getConstruct($file){
		$reader = new XMLReader();
		$reader->open($file, 'utf-8');
		
		$xmlArray = xml2assoc($reader);
		$arr = [];
		
		//遍历xml文件
		foreach($xmlArray as $location){
				
			$arrCountry = [];	
			if(is_array($location['value']))
			foreach($location['value'] as $i => $Country){
			
				//Country组装数组
				if(isset($Country['attributes']) && isset($Country['attributes']['Name'])){
					$countryName = $Country['attributes']['Name'];
					
					$arrCountry[$i]['name'] = $countryName;
					echo "<b>----------$countryName----------</b><br>";
				}
				
				$arrState = [];
				if(is_array($Country['value']))
				foreach($Country['value'] as $j => $State){
					
					//State组装数组
					if(isset($State['attributes']) && isset($State['attributes']['Name'])){
						$stateName = $State['attributes']['Name'];
						
						$arrState[$j]['name'] = $stateName;
						echo $stateName."<br>";
					}
					
					$arrCity = [];
					if(is_array($State['value']))
					foreach($State['value'] as $x => $City){
						
						//City组装数组
						if(isset($City['attributes']) && isset($City['attributes']['Name'])){
							$cityName = $City['attributes']['Name'];
							
							$arrCity[$x]['name'] = $cityName;
							echo "|--".$cityName."<br>";
						}
						
						$arrRegion = [];
						if(is_array($City['value']))
						foreach($City['value'] as $y => $Region){
							
							//Region组装数组
							if(isset($Region['attributes']) && isset($Region['attributes']['Name'])){
								$regionName = $Region['attributes']['Name'];
								
								$arrRegion[$y]['name'] = $regionName;
								echo "&emsp;|----".$regionName."<br>";
							}
							
						}//---------------Regions
						$arrCity[$x]["value"] = $arrRegion;
						
					}//---------------Citys
					$arrState[$j]["value"] = $arrCity;
					
				}//---------------States
				$arrCountry[$i]["value"] = $arrState;
				
			}//---------------Countrys
			$arr = $arrCountry;
			
			//var_dump($arr);
			return $arr;
		}
		
		/*foreach ($obj as $Country ) {
		
			//如果是已有country
			if($country == $Country['Name']){
				foreach ( $Country as $State ) {
					
					//如果是已有state
					if($state == $State['Name'])
						foreach ( $State as $City ) {
							
							//如果是已有city
							if($city == $City['Name'])
								foreach ( $City as $Region ) {
									
									//如果是已有region
									if($region == $Region['Name']){
										
									}
								}
						}
				}
			}else{
				//如果没有country
				$obj->Location->CountryRegion->Name = $country;
			}
		}
		
		$newxml = $obj->asXML(); //标准化 XML数据 
		$fp = fopen("LocList.xml", "w"); //打开要写入 XML数据的文件 
		fwrite($fp, $newxml); //写入 XML数据 
		fclose($fp); //关闭文件 */
	}
	
	//查找是否有对应节点，如果没有则添加进xml内
	function checkNode($file,$country="",$state="",$city="",$region=""){
		//遍历查找节点
		$doc = loadfile($file);

		$countryDom = $doc->getElementsByTagName("CountryRegion"); 

		// 循环遍历post标签 
		foreach($countryDom as $i => $Country){ 
			// 获取State标签Node 
			$stateDom = $Country->getElementsByTagName("State"); 
			
			/** 
			* 要获取State标签的Id属性要分两部走 
			* 1. 获取State中所有属性的列表也就是$State->item(0)->attributes 
			* 2. 获取State中id的属性，因为其在第一位所以用item(0) 
			* 
			* 小提示： 
			* 若取属性的值可以用item(*)->nodeValue 
			* 若取属性的标签可以用item(*)->nodeName 
			* 若取属性的类型可以用item(*)->nodeType 
			*/ 

			if($countryDom->item($i) && $countryDom->item($i)->attributes->item(0)){
				$countryName = $countryDom->item($i)->attributes->item(0)->nodeValue;
				echo "<b>----------$countryName----------</b><br>";
			}

			foreach($stateDom as $j => $State){
				$cityDom = $State->getElementsByTagName("City"); 
				
				if($stateDom->item($j) && $stateDom->item($j)->attributes->item(0)){
					$stateName = $stateDom->item($j)->attributes->item(0)->nodeValue;
					echo $stateName."<br>";
				}
				
				foreach($cityDom as $x => $City){
					$regionDom = $State->getElementsByTagName("Region"); 
					
					if($cityDom->item($x) && $cityDom->item($x)->attributes->item(0)){
						$cityName = $cityDom->item($x)->attributes->item(0)->nodeValue;
						echo "|--".$cityName."<br>";
					}
					
					foreach($regionDom as $y => $Region){
						if($regionDom->item($y) && $regionDom->item($y)->attributes->item(0)){
							$regionName = $regionDom->item($y)->attributes->item(0)->nodeValue;
							echo "&emsp;|----".$regionName."<br>";
						}
					}//---------------Regions
				}//---------------Citys
			}//---------------States
		}//---------------Countrys
		
	}
	
	function loadfile($file){
		$newfile = new DOMDocument();
		$newfile->validateonparse=true;
		$newfile->load($file);
	  
		return $newfile;
	}
	
	function add($file, $parentname, $children){ //增加xml节点
		$xml=loadfile($file);
	  

		$xml->save($file);
	}
	
	function delete($file, $id){//删除xml 节点
		$xml=loadfile($file);
	  

		$xml->save($file);
	}
	function edit($file, $id, $child, $value){//编辑xml 节点
		$xml=loadfile($file);

	  
		$xml->save($file);
	}
	
	$construct = checkNode('LocList.xml');
?>