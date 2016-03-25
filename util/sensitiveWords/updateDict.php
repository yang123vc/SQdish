<?php
    // 更新敏感词字典
    header("Content-type: text/html; charset=utf-8"); 
    include_once dirname(__FILE__).'/SimpleDict.php'; //过滤敏感字类
    $input = dirname(__FILE__)."/sw.txt";
    $dictPath = dirname(__FILE__).'/swDict.txt'; // 敏感字典
    SimpleDict::make($input, $dictPath);
    echo 'ok';
?>