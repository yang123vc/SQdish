<?php
/**
 * Created by PhpStorm.
 * User: Dean
 * Date: 2015/3/30
 * Time: 10:45
 */

function writeLog($whichLog = "payLog", $xitieOrderNo, $aliOrderNo, $tradeStatus, $tradeFee, $whichWay="RETURN"){
    date_default_timezone_set('Etc/GMT-8');
    $day = date('d');
    $year = date('Y');
    $month = date('m');
    $curTime = date('Y-m-d H:i:s');
    $dir  = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR."/log/$whichLog/$year/$month/$day";//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
    if(!file_exists($dir)){
        mkdir($dir, 0777, true); // 多级目录要设置第三个参数为true
    }
    $content = $xitieOrderNo." - ".$aliOrderNo." - ".$tradeStatus." - "."$tradeFee"." $curTime $whichWay\n";

    if($f  = file_put_contents($dir.'/log.txt', $content,FILE_APPEND)){// 这个函数支持版本(PHP 5)
//            echo "写入成功。<br />";
    }
}