<?php 
require_once('dbConnection.php');

include "checkSession.php"; 

$stmt = $conn->prepare("SELECT fileID, fileURL FROM file WHERE expiryDate <= now()");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) { 
    while ($row = $result->fetch_assoc()) {
         
        $fileID = $row["fileID"];
        $fileURL = $row["fileURL"];
        
        //Delete file from database   
        $stmt = $conn->prepare("DELETE FROM file WHERE fileID = ?");
        $stmt->bind_param("i", $fileID);
        $stmt->execute();

        //Delete file from directory
        if (file_exists($fileURL)) {
            unlink($fileURL);
        }
    }
}
$stmt->close();


//send notification when file expires, deleted.

//get current URL, if file does not exists, redirect to 404 
 if ($_SERVER['SCRIPT_NAME'] ==  "/ICT3103/file.php") {
     
    $fileHashing = $_GET["fID"];
    $fileID = 0;
    if (isset($_SESSION['fileArray'])) {
        foreach ($_SESSION['fileArray'] as $product) {
            if ($product['filePer'] == "private" || $product['filePer'] == "Private") {
                if ($product['hashID'] == $fileHashing) {
                    $fileID = $product['fileID'];
                    $countID = $product['countID'];
                    break;
                }
            } else {
                if ($product['fileID'] == $fileHashing) {
                    $fileID = $product['fileID'];
                    break;
                }
            }
        }
        
        //Query for file URL  
        $stmt = $conn->prepare("SELECT fileID FROM file WHERE fileID = ?");
        $stmt -> bind_param("i", $fileID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) { 
            header("Location: 404.php");
            exit();
        }
        
    } else {
        //Query for file URL  
        $stmt = $conn->prepare("SELECT f.filePermission FROM file f WHERE f.fileID = ?");
        $stmt->bind_param("i", $fileHashing);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            header ("Location: 404.php"); 
            exit();
        } else {
            $fileID = $fileHashing;
        }
    } 
    $stmt->close();
 } 
?>
