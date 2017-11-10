<?php
 
include "fileCheckPermission.php";
include "fileCheckExpiry.php";
if (!isset($_SESSION['SESS_ACC_ID'])) {
    header("Location: index.php");
}
 
$accountID = $_SESSION['SESS_ACC_ID'];
$fileHashing = $_GET["fID"];
$fileID = 0;
foreach ($_SESSION['fileArray'] as $product) {
    if ($product['hashID'] == $fileHashing) {
        $fileID = $product['fileID'];
        break;
    }
} 

require_once('dbConnection.php');
$stmt = $conn->prepare("SELECT a.name, f.accountID, f.fileName, f.fileURL, f.aesKey, f.fileType, f.fileSize, f.hash, f.filePermission, f.publicURL FROM file f INNER JOIN account a ON a.accountID = f.accountID WHERE f.fileID = ?");
$stmt->bind_param("i", $fileID);
$stmt->execute();
$result = $stmt->get_result(); 

if ($result->num_rows > 0) { //(result)
    //Loop tdrough tde result and print tde data to tde table
    while ($row = $result->fetch_assoc()) { 
        $uploaderID = $row["accountID"];
        $fileName = $row["fileName"];
        $fileURL = $row["fileURL"];
        $fileAESKey = $row["aesKey"];
        $fileType = $row["fileType"];
        $fileSize = round($row["fileSize"] / 1000.0 / 1000.0, 2) . "MB";
        $fileHash = $row["hash"]; 
        $filePermission = $row["filePermission"]; 
        $publicURL = $row["publicURL"];
    }
}
$stmt->close();

$realKey = ""; 

//Check if file uploader is the same as user who access the download website
if ($uploaderID == $accountID) { 
    $realKey = $fileAESKey;
} else {
    //Get the uploader file
    //check if owner itself has its filehsaring private key   
    $stmt = $conn->prepare("SELECT eAesKey FROM filesharing WHERE fileID = ? AND accountID = ?");
    $stmt->bind_param("ii", $fileID, $accountID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) { //(result)
        //Loop tdrough tde result and print tde data to tde table
        while ($row2 = $result->fetch_assoc()) { 
            $fileEAESKey = $row2["eAesKey"];  
        }
    } 
    $realKey = $fileEAESKey;
} 
//Do all the logic before closing connection. If not zip file will cause error.

 
//Create a zip file in the directory for download
$zip_name = $fileName . ".zip";
$zip = new ZipArchive(); 
$zip->open($zip_name, ZipArchive::CREATE);
if ($filePermission == "private") { 
    if (file_exists($fileURL)) {
        $zip->addFromString(basename($fileURL), file_get_contents($fileURL));
    }
    if (file_exists($realKey)) {
        $zip->addFromString(basename($realKey), file_get_contents($realKey));
    }
    
    //private but its not owner download
} else { //File permission is public, thus public download
    if (file_exists($publicURL)) {
        $zip->addFromString(basename($publicURL), file_get_contents($publicURL));
    }
}
$zip->close();


//Update file download times 
$stmt = $conn->prepare("UPDATE file SET downloadTimes = (downloadTimes + 1) WHERE fileID = ?");
$stmt->bind_param("i", $fileID);
$stmt->execute(); 
$stmt->close(); 

sleep(1);

//Force the website to download
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zip_name);
header('Content-Length: ' . filesize($zip_name));
readfile($zip_name); 


//Delete file from directory
if (file_exists($zip_name)) {
    unlink($zip_name);
}  

exit(); 
?>