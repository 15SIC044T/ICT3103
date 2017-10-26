<?php

include "fileCheckPermission.php"; 
include "fileCheckExpiry.php";

$conn = new Mysql_Driver();
$conn->connect();

$accountID = $_SESSION['SESS_ACC_ID'];
$fileID = $_GET["fID"];

$qry = "SELECT a.name, f.* FROM file f INNER JOIN account a ON a.accountID = f.accountID WHERE f.fileID = $fileID";
$result = $conn->query($qry);

if ($conn->num_rows($result) > 0) { //(result)
    //Loop tdrough tde result and print tde data to tde table
    while ($row = $conn->fetch_array($result)) {

        $uploadPerson = $row["name"];
        $uploaderID = $row["accountID"];
        $fileName = $row["fileName"];
        $fileURL = $row["fileURL"];
        $fileAESKey = $row["aesKey"];
        $fileType = $row["fileType"];
        $fileSize = round($row["fileSize"] / 1000.0 / 1000.0, 2) . "MB";
        $fileHash = $row["hash"];
        $FormatedUploadDate = $row["uploadDate"] == NULL ? "" : date("j M Y H:i:s A", strtotime($row["uploadDate"]));
        $FormatedExpiryDate = $row["expiryDate"] == NULL ? "" : date("j M Y H:i:s A", strtotime($row["expiryDate"]));
        $filePermission = $row["filePermission"];
        $fileStatus = $row["fileStatus"];
        $downloadTimes = $row["downloadTimes"];
    }
}
$conn->close();

$zip_name = $fileName . ".zip";
$zip = new ZipArchive();
//$zip_name = $fileName . ".zip"; // Zip name
$zip->open($zip_name, ZipArchive::CREATE);
if (file_exists($fileURL)) {
    $zip->addFromString(basename($fileURL), file_get_contents($fileURL));
}
if (file_exists($fileAESKey)) {
    $zip->addFromString(basename($fileAESKey), file_get_contents($fileAESKey));
}
$zip->close();

header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zip_name);
header('Content-Length: ' . filesize($zip_name));
readfile($zip_name);
exit();
?>