<?php

    include_once dirname(__FILE__).'/../include/taglib/xitie.lib.php';
    include_once dirname(__FILE__).'/../include/taglib/list.lib.php';
    include_once dirname(__FILE__).'/../include/taglib/switch.lib.php';
    include_once dirname(__FILE__).'/../entity/User.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of templateReader
 *
 * @author Dean
 */
class TemplateReader {
    //put your code here
    private $muid; // 喜帖id
    private $username; //用户名
    private $html = ""; // 输出文件内容
    private $pPath; // 物理输出路径
    private $webPath; // 输出文件网络路径
    private $webPathNoFilename; //不带文件的网络路径
    private $mid = -1; //模板id，PC版才传
    
    function handleTemplate($fileWebPath){

        // 生成的喜帖路径处理
        $muid = $this->getMuid();//获得muid
        global $db;
        if($db instanceof MysqliDb){
            $db->where('id', $muid);
            $result = $db->getOne("xt_module_user", "path");
        } else{
            $result = $db->get_one("select path from xt_module_user where id = $muid");
        }

        if(isset($result['path']) && strlen($result['path'])>0){ //如果有路径
            $OUTPUT_FILE_WEB_PATH = $result['path'];
            $length = strrpos($OUTPUT_FILE_WEB_PATH, "/")+1;
            $OUTPUT_WEB_PATH = substr($OUTPUT_FILE_WEB_PATH, 0, $length); //文件夹网络路径
            $OUTPUT_PATH = dirname(__FILE__)."/..".$OUTPUT_WEB_PATH; // 文件夹物理路径

            if($this->getMid() != -1){
                $OUTPUT_PATH = $OUTPUT_PATH."pc/";
                $OUTPUT_WEB_PATH = $OUTPUT_WEB_PATH."pc/";
            }
        } else{ //如果没有生成过喜帖，没有路径,就生成新路径
            $md = date("md");
            $year = date("Y");

            if(isset($_SESSION[USERSESSION])){
                $user = $_SESSION[USERSESSION];
                $userId = $user->getId();

                if($db instanceof MysqliDb){
                    $db->where('id', $userId);
                    $userInfo = $db->getOne("xt_user", "qq_openid, parentId");
                } else{
                    $userInfo = $db->get_one("select qq_openid, parentId from xt_user where id = $userId");
                }
                if(isset($userInfo['qq_openid']) && strlen($userInfo['qq_openid']) > 0){
                    $OUTPUT_PATH = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
                        .'userResource'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$md
                        .DIRECTORY_SEPARATOR.$userInfo['qq_openid'].DIRECTORY_SEPARATOR.$muid.DIRECTORY_SEPARATOR;

                    //html PNG location prefix
                    $OUTPUT_WEB_PATH = "/userResource/$year/$md/".$userInfo['qq_openid']."/$muid/";
                } elseif($userInfo['parentId'] != 0){
                    if($db instanceof MysqliDb){
                        $db->where('id', $userInfo['parentId']);
                        $parentInfo = $db->getOne("xt_user", "qq_openid");
                    } else{
                        $parentInfo = $db->get_one("select qq_openid from xt_user where id = ".$userInfo['parentId']);
                    }
                    if(isset($parentInfo['qq_openid']) && strlen($parentInfo['qq_openid']) > 0){
                        $OUTPUT_PATH = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
                            .'userResource'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$md
                            .DIRECTORY_SEPARATOR.$parentInfo['qq_openid'].DIRECTORY_SEPARATOR.$muid.DIRECTORY_SEPARATOR;

                        //html PNG location prefix
                        $OUTPUT_WEB_PATH = "/userResource/$year/$md/".$parentInfo['qq_openid']."/$muid/";
                    } else{
                        $OUTPUT_PATH = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
                            .'userResource'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$md
                            .DIRECTORY_SEPARATOR.$this->username.DIRECTORY_SEPARATOR.$muid.DIRECTORY_SEPARATOR;

                        //html PNG location prefix
                        $OUTPUT_WEB_PATH = "/userResource/$year/$md/$this->username/$muid/";
                    }
                } else{
                    $OUTPUT_PATH = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
                        .'userResource'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$md
                        .DIRECTORY_SEPARATOR.$this->username.DIRECTORY_SEPARATOR.$muid.DIRECTORY_SEPARATOR;

                    //html PNG location prefix
                    $OUTPUT_WEB_PATH = "/userResource/$year/$md/$this->username/$muid/";
                }

            } else{
                $OUTPUT_PATH = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
                        .'userResource'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$md
                        .DIRECTORY_SEPARATOR.$this->username.DIRECTORY_SEPARATOR.$muid.DIRECTORY_SEPARATOR;

                //html PNG location prefix
                $OUTPUT_WEB_PATH = "/userResource/$year/$md/$this->username/$muid/";
            }
            
        }



        // 模板网络路径转换为物理路径
        $filePath = dirname(__FILE__).'/..'.$fileWebPath;
        // 如果文件不存在, 退出方法
        if(!file_exists($filePath)){
            return;
        }
        // 获取当前文件的文件名
        $filename = basename($filePath); 
        
        $fp = fopen($filePath, 'rb'); // read only
        while (!feof($fp)){
            $currLine = fgets($fp);
            
            
            if(strstr($currLine,'{xitie:switch')){

                // 截取出switch
                $pos_start1 = strpos($currLine, "{xitie:switch");
                $pos_end1 = strpos($currLine, "}", $pos_start1); // 保证了{}的配对
                $pos_start1 = $pos_start1;
                $length1 = $pos_end1 - $pos_start1 + 1;
                $switch = substr($currLine, $pos_start1, $length1);
                $switch = trim($switch);
                // 截取出switchTag
                $pos_start = strpos($switch, " ");
                $pos_end = strpos($switch, "}");
                $pos_start = $pos_start+1;
                $length = $pos_end - $pos_start;
                $switchTag = substr($switch, $pos_start, $length);
                $switchTag = trim($switchTag);


                $innerCurr = ""; //标签内部当前读取行
                $innerAll = ""; //标签内部所有内容
                while(!strstr($innerCurr, '{/xitie:switch}')){
                    $innerCurr = fgets($fp); // 当前行内容
                    if(!strstr($innerCurr, '{/xitie:switch}')){ // 如果不是结束标签
                        $innerAll .= $innerCurr; // 则添加到标签内部的内容里
                    }
                }
                // 调用方法
                $currLine = switchDone($innerAll, $this->muid, $switchTag);
				$currLine = xitieDone($currLine, $this->muid,$OUTPUT_WEB_PATH, $this->mid);
            } elseif (strstr($currLine, '{xitie:list msg}')) {
                $innerCurr = ""; //标签内部当前读取行
                $innerAll = ""; //标签内部所有内容
                while(!strstr($innerCurr, '{/xitie:list}')){
                    $innerCurr = fgets($fp); // 当前行内容
                    if(!strstr($innerCurr, '{/xitie:list}')){ // 如果不是结束标签
                        $innerAll .= $innerCurr; // 则添加到标签内部的内容里
                    }
                }
                // 调用方法
                $currLine = listDone($innerAll, $this->muid, 'msg');
            } elseif(strstr($currLine, '{xitie:list pic}')){
                $innerCurr = ""; //标签内部当前读取行
                $innerAll = ""; //标签内部所有内容
                $index = -1; // 序号
                $innerAllInner = ""; //排序的内容
                while(!strstr($innerCurr, '{/xitie:list}')){
                    $innerCurr = fgets($fp); // 当前行内容
                    if(!strstr($innerCurr, '{/xitie:list}')){ // 如果不是结束标签
                        
                        if(strstr($innerCurr, '{xitie:list-index count=' )){ // 如果列表里有排序
                            $start = strpos($innerCurr, '{xitie:list-index count="')+1; // 
                            $end = strrpos($innerCurr, '"');
                            $length = $end - $start;
                            $index = (int)substr($innerCurr, $start, $length);
                            $innerCurrInner = "";
                            
                            while (!strstr($innerCurrInner, '{/xitie:list-index}')){
                                $innerCurrInner = fgets($fp);
                                if(!strstr($innerCurrInner, '{/xitie:list-index}')){
                                    $innerAllInner .= $innerCurrInner;
                                }
                            }
                            continue;
                        }
						
                        $innerAll .= $innerCurr; // 则添加到标签内部的内容里
                    }
                }
                // 调用方法
				
                $currLine = listDone($innerAll, $this->muid, 'pic', $innerAllInner, $index);
				$currLine = xitieDone($currLine, $this->muid, $OUTPUT_WEB_PATH, $this->mid);
            } elseif(strstr($currLine, '{xitie:')){ // 一般标签
                // 调用方法
                $currLine = xitieDone($currLine, $this->muid, $OUTPUT_WEB_PATH, $this->mid);
            }
            $this->html .= $currLine;
            
            
        }
        
        
        
        fclose($fp);

//        $muid = $this->getMuid();//获得muid
//        global $db;
//        if($db instanceof MysqliDb){
//            $db->where('id', $muid);
//            $result = $db->getOne("xt_module_user", "path");
//        } else{
//            $result = $db->get_one("select path from xt_module_user where id = $muid");
//        }
//
//        if(isset($result['path']) && strlen($result['path'])>0){ //如果有路径
//            $OUTPUT_FILE_WEB_PATH = $result['path'];
//            $length = strrpos($OUTPUT_FILE_WEB_PATH, "/")+1;
//            $OUTPUT_WEB_PATH = substr($OUTPUT_FILE_WEB_PATH, 0, $length); //文件夹网络路径
//            $OUTPUT_PATH = dirname(__FILE__)."/..".$OUTPUT_WEB_PATH; // 文件夹物理路径
//        } else{ //如果没有生成过喜帖，没有路径,就生成新路径
//            $md = date("Ymd");
//            $year = date("Y");
//
//            $OUTPUT_PATH = dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
//                .'userResource'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$md
//                .DIRECTORY_SEPARATOR.$this->username.DIRECTORY_SEPARATOR.$muid.DIRECTORY_SEPARATOR;
//
//            //html PNG location prefix
//            $OUTPUT_WEB_PATH = "/userResource/$year/$md/$this->username/$muid/";
//
//        }


        if (!file_exists($OUTPUT_PATH))
            mkdir($OUTPUT_PATH, 0777, true); // 多级目录要设置第三个参数为true

        $OUTPUT_FILE_PATH = $OUTPUT_PATH.$filename;

        $wfp = fopen($OUTPUT_FILE_PATH, 'w');

        fwrite($wfp, $this->html);
        fclose($wfp);

        $this->pPath = $OUTPUT_FILE_PATH;
        $this->webPath = $OUTPUT_WEB_PATH.$filename;
        $this->webPathNoFilename = $OUTPUT_WEB_PATH;

        return $this->html;
    }
    
    
    function preview($fileWebPath){
        // 网络路径转换为物理路径
        $filePath = dirname(__FILE__).'/..'.$fileWebPath;
        // 获取当前文件的文件名
        $filename = basename($filePath); 
        
        $fp = fopen($filePath, 'rb'); // read only
        while (!feof($fp)){
            $currLine = fgets($fp);
            
            
//            if(strstr($currLine,'{xitie:switch isMusic}')){
//                $innerCurr = ""; //标签内部当前读取行
//                $innerAll = ""; //标签内部所有内容
//                while(!strstr($innerCurr, '{/xitie:switch}')){
//                    $innerCurr = fgets($fp); // 当前行内容
//                    if(!strstr($innerCurr, '{/xitie:switch}')){ // 如果不是结束标签
//                        $innerAll .= $innerCurr; // 则添加到标签内部的内容里
//                    }
//                }
//                // 调用方法
//                $currLine = switchDone($innerAll, $this->muid, 'isMusic');
//            }


            if(strstr($currLine,'{xitie:switch')){

                // 截取出switch
                $pos_start1 = strpos($currLine, "{xitie:switch");
                $pos_end1 = strpos($currLine, "}", $pos_start1); // 保证了{}的配对
                $pos_start1 = $pos_start1;
                $length1 = $pos_end1 - $pos_start1 + 1;
                $switch = substr($currLine, $pos_start1, $length1);
                $switch = trim($switch);
                // 截取出switchTag
                $pos_start = strpos($switch, " ");
                $pos_end = strpos($switch, "}");
                $pos_start = $pos_start+1;
                $length = $pos_end - $pos_start;
                $switchTag = substr($switch, $pos_start, $length);
                $switchTag = trim($switchTag);


                $innerCurr = ""; //标签内部当前读取行
                $innerAll = ""; //标签内部所有内容
                while(!strstr($innerCurr, '{/xitie:switch}')){
                    $innerCurr = fgets($fp); // 当前行内容
                    if(!strstr($innerCurr, '{/xitie:switch}')){ // 如果不是结束标签
                        $innerAll .= $innerCurr; // 则添加到标签内部的内容里
                    }
                }
                // 调用方法
                $currLine = switchDone($innerAll, $this->muid, $switchTag);
                $currLine = xitieDone($currLine, $this->muid, "", $this->mid);
            } elseif (strstr($currLine, '{xitie:list msg}')) {
                $innerCurr = ""; //标签内部当前读取行
                $innerAll = ""; //标签内部所有内容
                while(!strstr($innerCurr, '{/xitie:list}')){
                    $innerCurr = fgets($fp); // 当前行内容
                    if(!strstr($innerCurr, '{/xitie:list}')){ // 如果不是结束标签
                        $innerAll .= $innerCurr; // 则添加到标签内部的内容里
                    }
                }
                // 调用方法
                $currLine = listDone($innerAll, $this->muid, 'msg');
				$currLine = xitieDone($currLine, $this->muid, "", $this->mid);
            } elseif(strstr($currLine, '{xitie:list pic}')){
                $innerCurr = ""; //标签内部当前读取行
                $innerAll = ""; //标签内部所有内容
                $index = -1; // 序号
                $innerAllInner = ""; //排序的内容
                while(!strstr($innerCurr, '{/xitie:list}')){
                    $innerCurr = fgets($fp); // 当前行内容
                    if(!strstr($innerCurr, '{/xitie:list}')){ // 如果不是结束标签
                        
                        if(strstr($innerCurr, '{xitie:list-index count=')){ // 如果列表里有排序
                            $start = strpos($innerCurr, '{xitie:list-index count="')+1; // 
                            $end = strrpos($innerCurr, '"');
                            $length = $end - $start;
                            $index = (int)substr($innerCurr, $start, $length);
                            $innerCurrInner = "";
                            
                            while (!strstr($innerCurrInner, '{/xitie:list-index}')){
                                $innerCurrInner = fgets($fp);
                                if(!strstr($innerCurrInner, '{/xitie:list-index}')){
                                    $innerAllInner .= $innerCurrInner;
                                }
                            }
                            continue;
                        }
						
                        $innerAll .= $innerCurr; // 则添加到标签内部的内容里
                    }
                }
                // 调用方法
				
                $currLine = listDone($innerAll, $this->muid, 'pic', $innerAllInner, $index);
				$currLine = xitieDone($currLine, $this->muid, "", $this->mid);
            } elseif(strstr($currLine, '{xitie:')){ // 一般标签
                // 调用方法
                $currLine = xitieDone($currLine, $this->muid, "", $this->mid);
            }
            $this->html .= $currLine;
            
            
        }
        
        
        
        fclose($fp);

        
        return $this->html;
    }
    
    
    
    
    function getMuid() {
        return $this->muid;
    }

    function getHtml() {
        return $this->html;
    }

    function getPPath() {
        return $this->pPath;
    }

    function getWebPath() {
        return $this->webPath;
    }

    function setMuid($muid) {
        $this->muid = $muid;
    }

    function setHtml($html) {
        $this->html = $html;
    }

    function setPPath($pPath) {
        $this->pPath = $pPath;
    }

    function setWebPath($webPath) {
        $this->webPath = $webPath;
    }

    function getUsername() {
        return $this->username;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getWebPathNoFilename()
    {
        return $this->webPathNoFilename;
    }

    /**
     * @param mixed $webPathNoFilename
     */
    public function setWebPathNoFilename($webPathNoFilename)
    {
        $this->webPathNoFilename = $webPathNoFilename;
    }

    /**
     * @return mixed
     */
    public function getMid()
    {
        return $this->mid;
    }

    /**
     * @param mixed $mid
     */
    public function setMid($mid)
    {
        $this->mid = $mid;
    }



    
}
