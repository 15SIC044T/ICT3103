<?php

$zip_name = "decrypter.php";

//Force the website to download
header('Content-Type: application/php');
header('Content-disposition: attachment; filename=' . $zip_name);
header('Content-Length: ' . filesize($zip_name));
readfile($zip_name);


?>