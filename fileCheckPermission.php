<?php
   
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
    
    // include database connection details
    include 'db-connection.php';
} 

$conn = new Mysql_Driver();  // Create an object for database access

$accountID = $_SESSION['SESS_ACC_ID'];
$fileID = $_GET["fID"];

//Query for file URL
$conn->connect();
$qry = "SELECT f.fileID FROM file f WHERE f.accountID = $accountID AND f.fileID = $fileID UNION 
        SELECT fs.fileID FROM filesharing fs WHERE fs.accountID = $accountID AND fs.fileID = $fileID";
$result = $conn->query($qry);
 
if ($conn->num_rows($result) == 0) {
    header ("Location: 404.php");
}

$conn->close();

?>