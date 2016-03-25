<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
    //获取主机名

    echo $_SERVER['SERVER_NAME'];
    echo "<br />";
    echo "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
?>