<?php
// start session
session_start();

include_once('db-connection.php'); 
$conn = new Mysql_Driver();

if (!empty($_FILES)) {
    $upload_dir = "uploads/";
    $fileName = $_FILES['file']['name'];
    $ext = pathinfo($upload_dir . $fileName);
    $uploaded_file = $upload_dir .  $_SESSION['SESS_ACC_ID'] . "_" . date("Ymd-hisa") . "_" . $fileName ;
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaded_file)) {

        echo "<script>javascript: alert('test msgbox')></script>";
        
        //insert file information into db table
        $conn->connect();
        $mysql_insert = "INSERT INTO file (accountID, fileName, fileURL, fileType, fileSize)VALUES('" . $_SESSION['SESS_ACC_ID'] . "','" . $ext['filename'] . "','" . $uploaded_file . "','" . $ext['extension'] . "','" . filesize($uploaded_file) . "')";
        $conn->query($mysql_insert); 
        $conn->close(); 
    }
}
?>