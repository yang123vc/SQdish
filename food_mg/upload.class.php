<?php
//封装文件上传类，调用UP()进行文件上传，返回上传后文件路径
defined('ACC')||exit('access denied');
class upload{
        public $path = '';

    //获取文件后缀
    protected function getExt($name){
       return substr($_FILES[$name]['type'],6);
    }
    //检测文件后缀
    protected function checkExt($ext){
        $arr = array('jpeg','jpg','png','gif');
        if(!in_array($ext,$arr)){
            echo '文件格式不对';
            exit;
        }   
    }
    //按日期生成目录
    protected function mk_dir(){
        $dir = dirname(__FILE__).'/../upload/icon/'.date('Ymd',time());
        if(!is_dir($dir)){
            mkdir($dir,0777,true);
        }
        return $dir;
    }
    //生成随机文件名
    protected function filename(){
        $str = 'abcdefghijkmnpqrstuvwxyz123456789';
        return substr(str_shuffle($str),0,6);
    }
    //生成路径
    protected function pathh($name){
        $ext = $this->getExt($name);
        $dir = $this->mk_dir();
        $filename= $this->filename();
        return  $path = $dir .'/' . $filename  . '.' . $ext;
    }
    //上传文件
    public function up($name){
        $ext = $this->getExt($name);
        $this->checkExt($ext);
        $path=$this->pathh($name);
            if(move_uploaded_file($_FILES[$name]['tmp_name'],$path)){
                       $path = str_replace(dirname(__FILE__).'/','',$path);
                       return $path;
            }else{
                       return false;    
            }
        }
    

}
//print_r($_FILES);
//调用up方法获取文件路径
/*
$upload = new upload();
$path = $upload->up('pic');
if($path){
    echo $path;
    echo '文件上传成功';
}else{
    echo '文件上传失败';
}

*/
?>