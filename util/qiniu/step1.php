<?php
/**
 * Created by PhpStorm.
 * User: Dean
 * Date: 2014/12/5
 * Time: 15:17
 */
header('Content-type: text/html;charset=UTF-8');
$scope = 'my-bucket:sunflower.jpg';
$deadline = 1451491200;
$returnBody = '{
  "name": $(fname),
  "size": $(fsize),
  "w": $(imageInfo.width),
  "h": $(imageInfo.height),
  "hash": $(etag)
}';

$putPolicy = '{"scope":"my-bucket:sunflower.jpg","deadline":1451491200,"returnBody":"{\"name\":$(fname),\"size\":$(fsize),\"w\":$(imageInfo.width),\"h\":$(imageInfo.height),\"hash\":$(etag)}"}';

//$encodedPutPolicy = urlsafe_base64_encode($putPolicy);

$encodedPutPolicy = base64_encode($putPolicy);

echo $encodedPutPolicy;
echo "<br />";

$sign = sha1($encodedPutPolicy, "MY_SECRET_KEY");

//$sign = hash_hmac($encodedPutPolicy, "MY_SECRET_KEY");
echo $sign;
