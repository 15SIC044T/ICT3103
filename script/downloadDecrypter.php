<?php

/*$fileURL = "decrypter.php";
$readme = "README.txt";

$zip_name = "decrypter.zip";
$zip = new ZipArchive(); 
$zip->open($zip_name, ZipArchive::CREATE);
if (file_exists($fileURL)) {
    $zip->addFromString(basename($fileURL), file_get_contents($fileURL));
}
if (file_exists($readme)) {
    $zip->addFromString(basename($readme), file_get_contents($readme));
}
$zip->close(); */

$zip_name = "decrypter.zip";
//Force the website to download
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zip_name);
header('Content-Length: ' . filesize($zip_name));
readfile($zip_name);


?>