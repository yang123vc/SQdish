<?php
require_once('../upyun.class.php');

$upyun = new UpYun('xtb123', 'uploader1', '123123abc');

try {
    echo "=========获取目录文件列表\r\n";
    $list = $upyun->getList('/demo/');
    var_dump($list);
    echo "=========DONE\r\n\r\n";
}
catch(Exception $e) {
    echo $e->getCode();
    echo $e->getMessage();
}
