<?
$old= array('10'=>'WIFI');
$new = array('1'=>'WIFI','3'=>'免费停车');
//$c = array_diff($old, $new);
//增加了
$d = array_diff($new, $old);
print_r($d);
?>