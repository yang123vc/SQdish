<?php

    // 删除文件夹工具类
    function deleteDir($path){
        if (PHP_OS === 'Windows' || PHP_OS === 'WINNT')
        {
            exec("rd /s /q {$path}");
        }
        else
        {
            exec("rm -rf {$path}");
        }
    }
?>
