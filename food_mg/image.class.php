<?php
class image{
    //生成随机字符
    static protected function randName($n){
        $str = "123456789abcdefghijkmnpqrstuvwxyz";
        return substr(str_shuffle($str),0,$n);
    } 

    //生成验证码
    static public function chekcode(){
        $im = imagecreatetruecolor(50,25);
        $gray = imagecolorallocate($im,100,100,100);
        $blue = imagecolorallocate($im,0,0,255);
        imagefill($im,0,0,$gray);
        $str = self::randName(4);
        $_SESSION['chekcode']=$str;
        imagestring($im,5,10,10,$str,$blue);
        header('content-type:image/png');
        return imagepng($im);
    }

    //获取图片信息长,宽,等
    static public function getInfo($ori){
        $arr = getimagesize($ori);
        //判断文件的格式是不是图片
        if(strstr($arr['mime'],'/',true)!=='image'){
            echo '文件不是图片';
            return false;
        }
        $info = array();
        //获取长宽
        $info['width'] = $arr['0'];
        $info['height'] = $arr['1'];
        //获取图片格式
        switch($arr['2']){
            case 1:$info['postfix'] = 'gif';break;
            case 2:$info['postfix'] = 'jpeg';break;
            case 3:$info['postfix'] = 'png';break;
            case 6:$info['postfix'] = 'bmp';break;
            default :$info['postfix'] = false;
        }
      
        return $info;
    }
    //等比生成缩略图
    static public function thumb($ori,$w=200,$h=200 ){
        $info = image::getInfo($ori);
         //获取原图
        $func1 = 'imagecreatefrom' . $info['postfix'];
        $big = $func1($ori);
        //生成缩略图模板
        $small = imagecreatetruecolor($w,$h);
        $gray = imagecolorallocate($small,252,252,252);
        imagefill($small,0,0,$gray);
        //获取缩放比例
        $k = min($w/$info['width'],$h/$info['height']);
        $width = $info['width']*$k;
        $height = $info['height']*$k;
          // 计算留白
        $lw = round(($w - $width)/2); // 计算左侧留的宽度
        $lh = round(($h - $height)/2); // 计算上部留的高度
        //生成缩略图
        imagecopyresampled($small,$big,$lw,$lh,0,0,$width,$height,$info['width'],$info['height']);
        //保存缩略图
        // 计算小图片的存储路径

        $thumburl = substr(str_replace('.','_thumb.',$ori),15);
        //echo $thumburl;exit;
        $func2 = 'image' . $info['postfix'];
        if($func2($small,'../'.$thumburl)){
            return str_replace(dirname(__FILE__),'',$thumburl);
        }else{
            return false;
        }
    }  

}
//$dd = "../resource/uediter/php/upload/20151121/14480943825639.jpg";
//cho image::thumb($dd);
//image::chekcode();




?>