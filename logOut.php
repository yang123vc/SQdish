<?php

    session_start();
    
    session_unset(); //1
    session_destroy(); //2
	
    header("location: http://www.sqdish.com");
?>