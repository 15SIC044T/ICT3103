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

$realKey = ""; 

//Check if file uploader is the same as user who access the download website
if ($uploaderID == $accountID) { 
    $realKey = $fileAESKey;
} else {
    //Get the uploader file
    //check if owner itself has its filehsaring private key  
    $qry2 = "SELECT eAesKey FROM filesharing WHERE fileID = $fileID AND accountID = $accountID"; 
    $result2 = $conn->query($qry2);

    if ($conn->num_rows($result2) > 0) { //(result)
        //Loop tdrough tde result and print tde data to tde table
        while ($row2 = $conn->fetch_array($result2)) {
 
            $fileEAESKey = $row2["eAesKey"];  
        }
    } 
    $realKey = $fileEAESKey;
}


//Do all the logic before closing connection. If not zip file will cause error.
$conn->close();

 
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
$conn->connect();
$qry = "UPDATE file SET downloadTimes = (downloadTimes + 1) WHERE fileID = $fileID";
$conn->query($qry);
$conn->close();

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