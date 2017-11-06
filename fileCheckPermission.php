<?php
   
include "checkSession.php";

if (!isset($_SESSION['SESS_ACC_ID'])) {
    header("Location: index.php");
} 

$accountID = $_SESSION['SESS_ACC_ID'];
$fileID = $_GET["fID"]; 
//Query for file URL  
$stmt = $conn->prepare("SELECT f.fileID FROM file f WHERE f.fileID = ? AND f.filePermission = 'public' UNION "
        . "SELECT f.fileID FROM file f WHERE f.accountID = ? AND f.fileID = ? UNION "
        . "SELECT fs.fileID FROM filesharing fs WHERE fs.accountID = ? AND fs.fileID = ?");
$stmt->bind_param("iiiii", $fileID, $accountID, $fileID, $accountID, $fileID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header ("Location: 404.php");
    exit();
} 
$stmt->close();

?>