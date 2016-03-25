<?php
	function getValue($key){
		global $db;
		$value = "";
		
		if($db){
			$sql = "select `value` from f_param where `key` = '$key'";
			$value = $db->get_one($sql);
		}
		
		return $value['value'];
	}
	
	function setValue($key,$value){
		global $db;
		$value = str_replace("'", "\'", $value);
		
		if($db){
			$sql = "update f_param set `value` = '$value' where `key` = '$key'";
			$value = $db->query($sql);
		}
	}
?>