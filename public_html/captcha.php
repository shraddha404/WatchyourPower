<?php
session_start();
$code=rand(1000,9999);
#$code=uniqid(rand(),true);
$_SESSION["code"]=$code;
$im = imagecreatetruecolor(50, 24);
//$bg = imagecolorallocate($im, 187, 217, 238); //background color blue
//$fg = imagecolorallocate($im, 255, 119, 0);//text color white
$bg = imagecolorallocate($im, 60, 141, 188); //background color blue
$fg = imagecolorallocate($im, 255, 255, 255);//text color white
imagefill($im, 0, 0, $bg);
imagestring($im, 5, 5, 5,  $code, $fg);
header("Cache-Control: no-cache, must-revalidate");
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
?>
