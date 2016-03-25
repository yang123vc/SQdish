<?php
/*
author: yantze
mail: yantze@126.com
$_FILES output: print_r($_FILES['file_pic']);
Array ( [file] => Array ( [name] => 无聊.txt [type] => text/plain [tmp_name] => /tmp/phpVZcAdK [error] => 0 [size] => 223 ) )
*/


function upload($file, $filename=NULL, $uploads_dir, $type_scope){

    //if type is correct
    $type_strict = false;
    $file_type = $file['type'];
    foreach($type_scope as $type) {
        if($type === $file_type) {
            $type_strict=true;
            break;
        }
    }

    //if dir exist && writable
    $dir_exist = file_exists($uploads_dir);
    if(!$dir_exist){ //如果路径不存在就创建
        mkdir($uploads_dir, 0777, true);
    }
    $dir_writable = is_writable($uploads_dir);
    $filename = $filename?$filename:$file['name'];
    if ( $type_strict && $dir_writable )
    {
        $ret = move_uploaded_file($file['tmp_name'], $uploads_dir . $filename);
        return $ret;
    }

    return false;
}

//unzip file
function unzip($src, $dst){
    $zip = new ZipArchive();
    $zip_file = $zip->open($src);
    if( $zip_file === true ) {
        $zip->extractTo($dst);
        $zip->close();

        unlink($src);
        return true;
    }else{
        return false;
    }
}
