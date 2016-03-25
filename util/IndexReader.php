<?php
/**
 * Created by PhpStorm.
 * User: Dean
 * Date: 2015/1/5
 * Time: 14:29
 */
header("Content-type: text/html; charset=utf-8");
include_once dirname(__FILE__).'/../include/taglib/xitie.lib.php';
include_once dirname(__FILE__).'/../include/taglib/index.lib.php';
include_once dirname(__FILE__).'/../include/taglib/indexList.lib.php';


class IndexReader {

    //put your code here

    private $html = ""; // 输出文件内容
    private $outputWebPath; // 输出文件网络路径
    private $inputWebPath; // 输入文件的路径
    private $mid; // 传入的mid

    private $baseDir; //当前文件到网站根目录的路径

    function __construct()
    {
        $this->baseDir = dirname(__FILE__).'/..'; //new的时候初始化当前文件到网站根目录的路径
    }

    // 获取引入的文件内容
    private function inc($currLine, $tagPre){

        // 截取出includeName
        $pos_start = strpos($currLine, $tagPre) + strlen($tagPre) - 1;
        $pos_end = strpos($currLine, " /}");
        $pos_start = $pos_start+1;
        $length = $pos_end - $pos_start;
        $incName = substr($currLine, $pos_start, $length);
        $incName = trim($incName);

        // 获取文件物理路径
        $incFile = $this->baseDir. "/new/inc/". $incName. ".html";

        if($incName == "footer"){
            $fp = fopen($incFile, 'rb'); // read only
            $innerHtml = "";
            while (!feof($fp)) {
                $currLine = fgets($fp); //一行一行的读
                if(strstr($currLine, '{listContent:')) {
                    $currLine = listContentDone($currLine);
                }
                $innerHtml .= $currLine;
            }
            return $innerHtml;
        } else{

            // 读取文件内容
            $content = file_get_contents($incFile);
            if($content){
                return $content; // 返回引入的内容
            }else{
                exit(); //如果没有匹配的文件就退出
            }
        }



    }

    // 获取tag参数值
    private function getTagValue($currLine, $tagPre){
        $start = strpos($currLine, $tagPre)+ strlen($tagPre);
        $end = strrpos($currLine, '}');
        $length = $end - $start;
        $val = trim(substr($currLine, $start, $length));
        return $val;
    }

    // 获取list之间的内容
    private function getListContent($fp){
        $innerCurr = ""; //标签内部当前读取行
        $innerAll = ""; //标签内部所有内容
        while(!strstr($innerCurr, '{/xitie:list}')){
            $innerCurr = fgets($fp); // 当前行内容
            if(!strstr($innerCurr, '{/xitie:list}')){ // 如果不是结束标签
                $innerAll .= $innerCurr; // 则添加到标签内部的内容里
            }
        }
        return $innerAll;
    }

    function handleTemplate(){

//        echo $this->getOutputWebPath();
//        echo "<br>";
//        echo $this->getInputWebPath();
//        echo "<br>";

        // 输入文件的物理路径
        $outputFilePath = $this->baseDir. $this->outputWebPath;
        $outputFilename = basename($outputFilePath);
        $outEnd = strpos($outputFilePath, $outputFilename);
        $outputDir = substr($outputFilePath, 0, $outEnd);

//        echo $outputDir;
//        echo "<br >";
//        exit();

        // 模板网络路径转换为物理路径
        $filePath = $this->baseDir. $this->inputWebPath;
        // 如果文件不存在, 退出方法
        if(!file_exists($filePath)){
            return;
        }


        $fp = fopen($filePath, 'rb'); // read only
        while (!feof($fp)){

            $currLine = fgets($fp); //一行一行的读

            if(strstr($currLine, '{include:')){
                // 返回引入的文件内容赋值给currLine
                $currLine = $this->inc($currLine, '{include:');
                //echo $currLine;
                //echo "<br >";

            } elseif(strstr($currLine, '{xitie:list module}')){ //{xitie:list module type=} 有type和没type
                // 获取list之间的内容
                $innerContent = $this->getListContent($fp);
                $currLine = listDone($innerContent, 'module');

            } elseif(strstr($currLine, '{xitie:list module type=')){
                // 获取list之间的内容
                $innerContent = $this->getListContent($fp);
                $type = $this->getTagValue($currLine, '{xitie:list module type=');
                $currLine = listDone($innerContent, 'module', $type);

            }elseif(strstr($currLine, '{xitie:list user}')){ //{xitie:list user mid=exist} {/xitie:list}
                // 获取list之间的内容
                $innerContent = $this->getListContent($fp);
                $currLine = listDone($innerContent, 'user');
            }elseif(strstr($currLine, '{xitie:list user mid=')){
                $mid = $this->getTagValue($currLine, '{xitie:list user mid=');
                if($mid == "exist"){ //使用传入的mid
                    $mid = $this->mid;
                }
                // 获取list之间的内容
                $innerContent = $this->getListContent($fp);
                $currLine = listDone($innerContent, 'user', '-1', $mid);
            } elseif(strstr($currLine, '{listContent:')){
                $currLine = listContentDone($currLine);
            } elseif(strstr($currLine, '{item:')){
                $currLine = moduleItemDone($currLine, $this->mid);
            }

            $this->html .= $currLine;

        }


        fclose($fp);


        if (!file_exists($outputDir))
            mkdir($outputDir, 0777, true); // 多级目录要设置第三个参数为true

        $wfp = fopen($outputFilePath, 'w');

        fwrite($wfp, $this->html);
        fclose($wfp);

        return $this->html;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @return mixed
     */
    public function getInputWebPath()
    {
        return $this->inputWebPath;
    }

    /**
     * @param mixed $inputWebPath
     */
    public function setInputWebPath($inputWebPath)
    {
        $this->inputWebPath = $inputWebPath;
    }

    /**
     * @return mixed
     */
    public function getOutputWebPath()
    {
        return $this->outputWebPath;
    }

    /**
     * @param mixed $outputWebPath
     */
    public function setOutputWebPath($outputWebPath)
    {
        $this->outputWebPath = $outputWebPath;
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