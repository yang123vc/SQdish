<?php
//+---------------------------------------
//|  系统函数
//+---------------------------------------

/**
 * 图片裁剪函数，支持指定定点裁剪和方位裁剪两种裁剪模式
 * @param <string>  $src_file       原图片路径
 * @param <int>     $new_width      裁剪后图片宽度（当宽度超过原图片宽度时，去原图片宽度）
 * @param <int>     $new_height     裁剪后图片高度（当宽度超过原图片宽度时，去原图片高度）
 * @param <int>     $type           裁剪方式，1-方位模式裁剪；0-定点模式裁剪。
 * @param <int>     $pos            方位模式裁剪时的起始方位（当选定点模式裁剪时，此参数不起作用）
 *                                      1为顶端居左，2为顶端居中，3为顶端居右； 
 *                                      4为中部居左，5为中部居中，6为中部居右； 
 *                                      7为底端居左，8为底端居中，9为底端居右；
 * @param <int>     $start_x        起始位置X （当选定方位模式裁剪时，此参数不起作用）
 * @param <int>     $start_y        起始位置Y（当选定方位模式裁剪时，此参数不起作用）
 * @return <string>                 裁剪图片存储路径
 */
function thumb($src_file, $new_width, $new_height, $type = 1, $pos = 5, $start_x = 0, $start_y = 0) {
    $pathinfo = pathinfo($src_file);
    //$dst_file = $pathinfo['dirname'] . '/' . $pathinfo['filename'] .'_'. $new_width . 'x' . $new_height . '.' . $pathinfo['extension'];
    $dst_file = $pathinfo['dirname'] . DIRECTORY_SEPARATOR . $pathinfo['filename'] .'_crop.'. $pathinfo['extension'];
    if (!file_exists($dst_file)) {
        if ($new_width < 1 || $new_height < 1) {
            echo "params width or height error !";
            exit();
        }
        if (!file_exists($src_file)) {
            echo $src_file . " is not exists !";
            exit();
        }
        // 图像类型
        //$img_type = exif_imagetype($src_file);
		$img_type = pathinfo($src_file,PATHINFO_EXTENSION); 		 
        $support_type = array('gif', 'jpg', 'png','GIF','JPG','PNG');

        if (!in_array($img_type, $support_type, true)) {
            echo "只支持jpg、png、gif格式图片裁剪";
            exit();
        }
        /* 载入图像 */
        switch ($img_type) {
            case 'jpg' :
                $src_img = imagecreatefromjpeg($src_file);
                break;
			case 'JPG' :
                $src_img = imagecreatefromjpeg($src_file);
                break;
            case 'png' :
                $src_img = imagecreatefrompng($src_file);
                break;
			case 'PNG' :
                $src_img = imagecreatefrompng($src_file);
                break;
			case 'GIF' :
                $src_img = imagecreatefromgif($src_file);
                break;
            case 'gif' :
                $src_img = imagecreatefromgif($src_file);
                break;
            default:
            echo "载入图像错误!";
            exit();
        }
        /* 获取源图片的宽度和高度 */
        $src_width = imagesx($src_img);
        $src_height = imagesy($src_img);
        /* 计算剪切图片的宽度和高度 */
        $mid_width = ($src_width < $new_width) ? $src_width : $new_width;
        $mid_height = ($src_height < $new_height) ? $src_height : $new_height;
        /* 初始化源图片剪切裁剪的起始位置坐标 */
        switch ($pos * $type) {
            case 1://1为顶端居左 
                $start_x = 0;
                $start_y = 0;
                break;
            case 2://2为顶端居中 
                $start_x = ($src_width - $mid_width) / 2;
                $start_y = 0;
                break;
            case 3://3为顶端居右 
                $start_x = $src_width - $mid_width;
                $start_y = 0;
                break;
            case 4://4为中部居左 
                $start_x = 0;
                $start_y = ($src_height - $mid_height) / 2;
                break;
            case 5://5为中部居中 
                $start_x = ($src_width - $mid_width) / 2;
                $start_y = ($src_height - $mid_height) / 2;
                break;
            case 6://6为中部居右 
                $start_x = $src_width - $mid_width;
                $start_y = ($src_height - $mid_height) / 2;
                break;
            case 7://7为底端居左 
                $start_x = 0;
                $start_y = $src_height - $mid_height;
                break;
            case 8://8为底端居中 
                $start_x = ($src_width - $mid_width) / 2;
                $start_y = $src_height - $mid_height;
                break;
            case 9://9为底端居右 
                $start_x = $src_width - $mid_width;
                $start_y = $src_height - $mid_height;
                break;
            default://随机 
                break;
        }
        // 为剪切图像创建背景画板
        $mid_img = imagecreatetruecolor($mid_width, $mid_height);
        //拷贝剪切的图像数据到画板，生成剪切图像
        imagecopy($mid_img, $src_img, 0, 0, $start_x, $start_y, $mid_width, $mid_height);
        // 为裁剪图像创建背景画板
        $new_img = imagecreatetruecolor($new_width, $new_height);
        //拷贝剪切图像到背景画板，并按比例裁剪
        imagecopyresampled($new_img, $mid_img, 0, 0, 0, 0, $new_width, $new_height, $mid_width, $mid_height);

        /* 按格式保存为图片 */
        switch ($img_type) {
            case 'JPG' :
                imagejpeg($new_img, $dst_file, 100);
                break;
			case 'jpg' :
                imagejpeg($new_img, $dst_file, 100);
                break;
            case 'PNG' :
                imagepng($new_img, $dst_file, 100);
                break;
			case 'png' :
                imagepng($new_img, $dst_file, 100);
                break;
            case 'GIF' :
                imagegif($new_img, $dst_file, 100);
                break;
			case 'gif' :
                imagegif($new_img, $dst_file, 100);
                break;
            default:
                break;
        }
    }
    return ltrim($dst_file, '.');
}

function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
{
    $pic_width = imagesx($im);
    $pic_height = imagesy($im);

    if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
    {
        if($maxwidth && $pic_width>$maxwidth)
        {
            $widthratio = $maxwidth/$pic_width;
            $resizewidth_tag = true;
        }

        if($maxheight && $pic_height>$maxheight)
        {
            $heightratio = $maxheight/$pic_height;
            $resizeheight_tag = true;
        }

        if($resizewidth_tag && $resizeheight_tag)
        {
            if($widthratio<$heightratio)
                $ratio = $widthratio;
            else
                $ratio = $heightratio;
        }

        if($resizewidth_tag && !$resizeheight_tag)
            $ratio = $widthratio;
        if($resizeheight_tag && !$resizewidth_tag)
            $ratio = $heightratio;

        $newwidth = $pic_width * $ratio;
        $newheight = $pic_height * $ratio;

        if(function_exists("imagecopyresampled"))
        {
            $newim = imagecreatetruecolor($newwidth,$newheight);
           imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }
        else
        {
            $newim = imagecreate($newwidth,$newheight);
           imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
        }

        $name = $name.$filetype;
        imagejpeg($newim,$name);
        imagedestroy($newim);
    }
    else
    {
        $name = $name.$filetype;
        imagejpeg($im,$name);
    }           
}

//var_dump(thumb('./images/2.jpg',100,100,0,0,100,100));

?>