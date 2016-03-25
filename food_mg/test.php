<?php
header("content-Type: text/html; charset=utf8");
$uptypes=array('image/jpg', //上传文件类型列表
'image/jpeg',
'image/png',
'image/pjpeg',
'image/gif',
'image/bmp',
'application/x-shockwave-flash',
'image/x-png',
'application/msword',
'audio/x-ms-wma',
'audio/mp3',
'application/vnd.rn-realmedia',
'application/x-zip-compressed',
'application/octet-stream');
$max_file_size=1000000; //上传文件大小限制, 单位BYTE
$path_parts=pathinfo($_SERVER['PHP_SELF']); //取得当前路径
$destination_folder="up/"; //上传文件路径
$watermark=1; //是否附加水印(1为加水印,0为不加水印);
$watertype=1; //水印类型(1为文字,2为图片)
$waterposition=2; //水印位置(1为左下角,2为右下角,3为左上角,4为右上角,5为居中);
$waterstring="www.tt365.org"; //水印字符串
$waterimg="xplore.gif"; //水印图片
$imgpreview=1; //是否生成预览图(1为生成,0为不生成);
$imgpreviewsize=1/1; //缩略图比例
?>
<html xmlns="undefined">
<head>
<title>图片上传储存</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<meta name="description" content="365上网导航,365网址导航" />
<style type="text/css">
body,td{font-family:tahoma,verdana,arial;font-size:11px;line-height:15px;background-color:white;color:#666666;
strong{font-size:12px;}
a:link{color:#0066CC;}
a:hover{color:#FF6600;}
a:visited{color:#003366;}
a:active{color:#9DCC00;}
a{TEXT-DECORATION:none}
td.irows{height:20px;background:url("index.php?i=dots") repeat-x bottom}
</style>
</head>
<script type="text/javascript">function oCopy(obj){obj.select();js=obj.createTextRange();js.execCommand("Copy");};function sendtof(url){window.clipboardData.setData('Text',url);alert('复制地址成功，粘贴给你好友一起分享。');};function select_format(){var on=document.getElementByIdx_x('fmt').checked;document.getElementByIdx_x('site').style.display=on?'none':'';document.getElementByIdx_x('sited').style.display=!on?'none':'';};var flag=false;function DrawImage(ImgD){var image=new Image();image.src=ImgD.src;if(image.width>0&&image.height>0){flag=true;if(image.width/image.height>=120/80){if(image.width>120){ImgD.width=120;ImgD.height=(image.height*120)/image.width;}else {ImgD.width=image.width;ImgD.height=image.height;};ImgD.alt=image.width+"×"+image.height;}else {if(image.height>80){ImgD.height=80;ImgD.width=(image.width*80)/image.height;}else {ImgD.width=image.width;ImgD.height=image.height;};ImgD.alt=image.width+"×"+image.height;}};};function FileChange(Value){flag=false;document.all.uploadimage.width=10;document.all.uploadimage.height=10;document.all.uploadimage.alt="";document.all.uploadimage.src=Value;};</script>
<body bgcolor="#FFFFFF">
<center>
<form enctype="multipart/form-data" method="post" name="upform">
<table border="1" width="55%" id="table1" cellspacing=0>
<tr>
<td colspan="2"><p align="center">最大文件限制1M </td>
</tr>
<tr>
<td width="10%"><div style="width:120px; height:80px;overflow:hidden;text-align: center;" ><IMG id=uploadimage height=0 width=0 src="" onload="javascript:DrawImage(this);" ></div></td>
<td width="71%"><div style="width:361px; height:80px;overflow:hidden;text-align: center;padding:30px; " >
<input style="width:208;border:1 solid #9a9999; font-size:9pt; background-color:#ffffff; height:18" size="17" name=upfile type=file
onchange="javascript:FileChange(this.value);">
<input type="submit" value="上传" style="width:60;border:1 solid #9a9999; font-size:9pt; background-color:#ffffff; height:18" size="17"></td>
</tr>
</table>
允许上传的文件类型为:jpg|jpeg|gif|bmp|png|swf|mp3|wma|zip|rar|doc</form>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
if (!is_uploaded_file($_FILES["upfile"][tmp_name]))
//是否存在文件
{
echo "<font color='red'>文件不存在！</font>";
exit;
}
$file = $_FILES["upfile"];
if($max_file_size < $file["size"])
//检查文件大小
{
echo "<font color='red'>文件太大！</font>";
exit;
}
if(!in_array($file["type"], $uptypes))
//检查文件类型
{
echo "<font color='red'>不能上传此类型文件！</font>";
exit;
}
if(!file_exists($destination_folder))
mkdir($destination_folder);
$filename=$file["tmp_name"];
$image_size = getimagesize($filename);
$pinfo=pathinfo($file["name"]);
$ftype=$pinfo[extension];
$destination = $destination_folder.time().".".$ftype;
if (file_exists($destination) && $overwrite != true)
{
echo "<font color='red'>同名文件已经存在了！</a>";
exit;
}
if(!move_uploaded_file ($filename, $destination))
{
echo "<font color='red'>移动文件出错！</a>";
exit;
}
$pinfo=pathinfo($destination);
$fname=$pinfo[basename];
echo " <font color=red>成功上传,鼠标移动到地址栏自动复制</font><br><table width=\"348\" cellspacing=\"0\" cellpadding=\"5\" border=\"0\" class=\"table_decoration\" align=\"center\"><tr><td><input type=\"checkbox\" id=\"fmt\" onclick=\"select_format()\"/>图片UBB代码<br/><div id=\"site\"><table border=\"0\"><tr><td valign=\"top\">文件地址:</td><td><input type=\"text\" onclick=\"sendtof(this.value)\" onmouseover=\"oCopy(this)\" style=font-size=9pt;color:blue size=\"44\" value=\"http://".$_SERVER['SERVER_NAME'].$path_parts["dirname"]."/".$destination_folder.$fname."\"/>
</td></tr></table></div><div id=\"sited\" style=\"display:none\"><table border=\"0\"><tr><td valign=\"top\">文件地址:</td><td><input type=\"text\" onclick=\"sendtof(this.value)\" onmouseover=\"oCopy(this)\" style=font-size=9pt;color:blue size=\"44\" value=\"[img]http://".$_SERVER['SERVER_NAME'].$path_parts["dirname"]."/".$destination_folder.$fname."[/img]\"/></td></tr></table></div></td></tr></table>";
echo " 宽度:".$image_size[0];
echo " 长度:".$image_size[1];
if($watermark==1)
{
$iinfo=getimagesize($destination,$iinfo);
$nimage=imagecreatetruecolor($image_size[0],$image_size[1]);
$white=imagecolorallocate($nimage,255,255,255);
$black=imagecolorallocate($nimage,0,0,0);
$red=imagecolorallocate($nimage,255,0,0);
imagefill($nimage,0,0,$white);
switch ($iinfo[2])
{
case 1:
$simage =imagecreatefromgif($destination);
break;
case 2:
$simage =imagecreatefromjpeg($destination);
break;
case 3:
$simage =imagecreatefrompng($destination);
break;
case 6:
$simage =imagecreatefromwbmp($destination);
break;
default:
die("<font color='red'>不能上传此类型文件！</a>");
exit;
}
imagecopy($nimage,$simage,0,0,0,0,$image_size[0],$image_size[1]);
imagefilledrectangle($nimage,1,$image_size[1]-15,80,$image_size[1],$white);
switch($watertype)
{
case 1: //加水印字符串
imagestring($nimage,2,3,$image_size[1]-15,$waterstring,$black);
break;
case 2: //加水印图片
$simage1 =imagecreatefromgif("xplore.gif");
imagecopy($nimage,$simage1,0,0,0,0,85,15);
imagedestroy($simage1);
break;
}
switch ($iinfo[2])
{
case 1:
//imagegif($nimage, $destination);
imagejpeg($nimage, $destination);
break;
case 2:
imagejpeg($nimage, $destination);
break;
case 3:
imagepng($nimage, $destination);
break;
case 6:
imagewbmp($nimage, $destination);
//imagejpeg($nimage, $destination);
break;
}
//覆盖原上传文件
imagedestroy($nimage);
imagedestroy($simage);
}
if($imgpreview==1)
{
echo "<br>图片预览:<br>";
echo "<a href=\"".$destination."\" target='_blank'><img src=\"".$destination."\" width=".($image_size[0]*$imgpreviewsize)." height=".($image_size[1]*$imgpreviewsize);
echo " alt=\"图片预览:\r文件名:".$fname."\r上传时间:".date('m/d/Y h:i')."\" border='0'></a>";
}
}
?>
</center>
</body>
</html>