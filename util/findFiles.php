<?php
    /* 定义自定义函数 */
    /**
     * 获取文件列表
     * 
     * @param string  $dir  欲读取的目录路径
     * @param boolean $mode 0：读取全部；1：仅读取文件；2：仅读取目录
     * @return array
     */
    function readFolder($webdir, $muid, $limit = 12, $mode = 1) {
        
        global $db;
        
        $dir = dirname(__FILE__).'/..'.$webdir.'/'; //返回物理路径
        
        //如果打开目录句柄失败，则输出空数组
        if (!$handle = opendir($dir)) return array();
		
        //定义文件列表数组
        $files = array();
        //遍历目录句柄中的条目
        while (false !== ($file = @readdir($handle))) {
			//跳过本目录以及上级目录
			if ('.' === $file || '..' === $file) continue;
			//是否仅读取目录
			if ($mode === 2) {
			if (isDir($dir . '/' . $file)) $files[] = $file;
			//是否仅读取文件
			} elseif ($mode === 1) {
				if (isFile($dir . '/' . $file)) {
					$path = $dir . '/' . $file;
					$filetime[] = date("Y-m-d H:i:s",filemtime($path));
					$files[] = $file;
				}
			//读取全部
			} else {
				$files[] = $file;
			}
        }
		
        //关闭打开的目录句柄
        closedir($handle);
		
		array_multisort($filetime,SORT_ASC,SORT_STRING, $files);

        //输出文件列表数组
        if($db instanceof MysqliDb){
            $db->where("muid", $muid);
            $db->delete("xt_module_user_pic");
        } else{
            mysql_query("delete from xt_module_user_pic where muid = $muid");
        }

        $index = 0;
		$fileSize = count($files);

		foreach($files as $value){
            if($index >= $limit){ //大于限制的删掉
                unlink($dir.$value);
            } else{
                $filePath = $webdir.'/'.$value;
                if($db instanceof MysqliDb){
                    $params = array('muid'=>$muid, 'fileName'=>$value, 'picPath'=>$filePath, '_order'=>$index);
                    $db->insert("xt_module_user_pic", $params);
                } else{
                    mysql_query("insert into xt_module_user_pic (muid, fileName, picPath, `_order`) values ('$muid', '$value', '$filePath', $index)");
                }
                
            }
            $index++;
        }
        
        if($fileSize > $limit){
            return -1; //超出限制
        } else{
            return 1; //没有超出
        }
    }
    /**
     * 判断输入是否为目录
     *
     * @param string $dir
     * @return boolean
     */
    function isDir($dir) {
        return $dir ? is_dir($dir) : false;
    }
    /**
     * 判断输入是否为文件
     *
     * @param string $file
     * @return boolean
     */
    function isFile($file) {
        return $file ? is_file($file) : false;
    }
