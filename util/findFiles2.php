<?php
    //设定报错等级，如果是开源程序插件不需要设定
    error_reporting(E_ERROR | E_PARSE);
    //定义欲读取的目录路径，方便演示，本程序读取的是当前文件所在目录
    $path = dirname(__FILE__).'/../userImg/';
    //获取文件列表数组
    $files = ReadFolder($path);
    //处理文件列表数组
    foreach ($files as $value) {
    //显示文件链接
    echo '<a href="' . $value . '">' . $value . '</a>';
    //为方便查看，输出一个 <br /> 换行符
    echo '<br />';
    }
    /* 定义自定义函数 */
    /**
     * 获取文件列表
     * 
     * @param string  $dir  欲读取的目录路径
     * @param boolean $mode 0：读取全部；1：仅读取文件；2：仅读取目录
     * @return array
     */
    function ReadFolder($dir, $mode = 0) {
    //如果打开目录句柄失败，则输出空数组
    if (!$handle = @opendir($dir)) return array();
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
    if (isFile($dir . '/' . $file)) $files[] = $file;
    //读取全部
    } else {
    $files[] = $file;
    }
    }
    //关闭打开的目录句柄
    @closedir($handle);
    //输出文件列表数组
    return $files;
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
?>
