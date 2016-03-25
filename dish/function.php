<?php
	function getTotalResult($cart){
		$totalPrice = 0;
		$totalNum = 0;
		$classNum = "";
		$result = "";
	
		if($cart){
			foreach($cart as $k1 => $v1){
				$classNum[$k1] = 0;
				
				foreach($v1 as $k2 => $v2){
					if(isset($v2['num'])){
						$totalPrice += $v2['price'] * $v2['num'];
						$totalNum += $v2['num'];
						$classNum[$k1] += $v2['num'];
					}
				}
			}
		}
		
		$result['totalPrice'] = $totalPrice;
		$result['totalNum'] = $totalNum;
		$result['classNum'] = $classNum;
		
		return $result;
	}
	
	function merge($cart,$ocCart){
		
	}
?>