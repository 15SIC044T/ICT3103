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
 
        $fileName = $row["fileName"];
        $fileURL = $row["fileURL"];
        $fileAESKey = $row["aesKey"];
        $fileType = $row["fileType"];
        $fileSize = round($row["fileSize"] / 1000.0 / 1000.0, 2) . "MB";
        $fileHash = $row["hash"]; 
        $filePermission = $row["filePermission"]; 
        //$publicURL = $row["publicURL"];
    }
}
$conn->close();

if ($filePermission == "private") {
    
    //check if owner itself has its filehsaring private key 
    $conn->connect(); 
    $qry = "SELECT eAesKey FROM filesharing WHERE fileID = $fileID AND accountID = $accountID"; 
    $result = $conn->query($qry);

    if ($conn->num_rows($result) > 0) { //(result)
        //Loop tdrough tde result and print tde data to tde table
        while ($row = $conn->fetch_array($result)) {
 
            $fileEAESKey = $row["eAesKey"]; 
        }
    }
    /*else { 
        //Insert file sharing, so it shares its file to itself
        $qry2 = "SELECT f.aesKey, a.publicKey FROM file f, account a WHERE f.fileID = ". $fileID ." AND a.accountID = ". $accountID;
        $result2 = $conn->query($qry2);

        if ($conn->num_rows($result2) > 0) {
            while ($row = $conn->fetch_array($result2)) {
                $aKey = $row["aesKey"];
                $pKey = $row["publicKey"];
            }
        } 
        $pKey = substr($pKey, 3);
        $eAes = "keys/eAes/" . $fileID . "_" . $accountID . "_" . date("Y-m-d_H-i-s",time()) . "_eAes.key";
        $data = file_get_contents($aKey);
        $publicKey = file_get_contents($pKey);
        openssl_public_encrypt($data, $encrypted, $publicKey);
        file_put_contents($eAes, $encrypted);

        //Insert the sharing emails
        $qryInsert = "INSERT INTO filesharing (fileID, accountID, invitationAccepted, eAesKey, owner) VALUES ($fileID, $accountID, 1, '$eAes', 1)";
        $conn->query($qryInsert);
    }*/
    $conn->close();
 } 
 
    $zip_name = $fileName . ".zip";
    $zip = new ZipArchive(); 
    $zip->open($zip_name, ZipArchive::CREATE);
    
    if ($filePermission == "private") {
        if (file_exists($fileURL)) {
            $zip->addFromString(basename($fileURL), file_get_contents($fileURL));
        }
        if (file_exists($fileEAESKey)) {
            $zip->addFromString(basename($fileEAESKey), file_get_contents($fileEAESKey));
        } 
        /*{   //If sharing is not found, means, its the owner file
            if (file_exists($fileAESKey)) {
                $zip->addFromString(basename($fileAESKey), file_get_contents($fileAESKey));
            } 
        }*/
    }
    else {
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