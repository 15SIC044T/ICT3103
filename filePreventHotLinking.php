<?php
$dir='uploads/';
if ((!$file=realpath($dir.$_GET['file']))
    || strpos($file,realpath($dir))!==0 || substr($file,-4)=='.php'){
  header('HTTP/1.0 404 Not Found');
  exit();
}
$ref=$_SERVER['HTTP_REFERER'];
if (strpos($ref,'localhost/ICT3103')===0 || strpos($ref,'http')!==0){
  $mime=array(
    'jpg'=>'image/jpeg',
    'png'=>'image/png',
    'jpg'=>'’image/jpg',
  );
  $stat=stat($file);
  header('Content-Type: '.$mime[substr($file,-3)]);
  header('Content-Length: '.$stat[7]);
  header('Last-Modified: '.gmdate('D, d M Y H:i:s',$stat[9]).' GMT');
  readfile($file);
  exit();
}
header('Pragma: no-cache');
header('Cache-Control: no-cache, no-store, must-revalidate');
include($file.'.php');
?>