<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    // 用户模板文件夹，用户模板文件路径 返回二维码路径
    function qrcodeGenerator($storeWebPath, $muPath, $level='H', $size=10){

         //set it to writable location, a place for user's qrcodeImg generated PNG files
        $PNG_TEMP_DIR = dirname(__FILE__).'/../../'.$storeWebPath;
//        $PNG_TEMP_DIR = str_replace("/", DIRECTORY_SEPARATOR, $PNG_TEMP_DIR);

        //html PNG location prefix
        $PNG_WEB_DIR = $storeWebPath;

        include "qrlib.php";    
        
        //ofcourse we need rights to create temp dir
        if (!file_exists($PNG_TEMP_DIR)){
            echo "file is not exist";
            mkdir($PNG_TEMP_DIR, 0777, true); // 多级目录要设置第三个参数为true
        }


        //processing form input
        //remember to sanitize user input in real-life solution !!!
//        $errorCorrectionLevel = 'L';
        if (isset($level) && in_array($level, array('L','M','Q','H')))
            $errorCorrectionLevel = $level;    

//        $matrixPointSize = 4;
        if (isset($size))
            $matrixPointSize = min(max((int)$size, 1), 10);


        if (isset($muPath)) { 

            //it's very important!
            if (trim($muPath) == '')
                die('data cannot be empty!');

            // user data
            $filename = $PNG_TEMP_DIR.md5($muPath.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
            QRcode::png($muPath, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
            return $PNG_WEB_DIR.basename($filename); //return a web path
        } else {    

            //muPath can not be nothing
            die('you must provide a muPath');

        }    
        
    }

