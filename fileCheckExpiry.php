<?php
ob_start();
$conn = new Mysql_Driver();  // Create an object for database access

//Query for file URL
$conn->connect();
$qry = "SELECT * FROM file WHERE expiryDate <= date('Y-m-d H:i:s')";
$result = $conn->query($qry);
 
if ($conn->num_rows($result) > 0) { 
    while ($conn->num_rows($result) > 0) {
        
        $row = $conn->fetch_array($result);
        $fileID = $row["fileID"];
        $fileURL = $row["fileURL"];
        //Delete file from database 
        $qryDelete = "DELETE FROM file WHERE fileID = $fileID";
        $conn->query($qryDelete);

        //Delete file from directory
        if (file_exists($fileURL)) {
            unlink($fileURL);
        }
    }
}
$conn->close();

//get current URL, if file does not exists, redirect to 404 
 if ($_SERVER['SCRIPT_NAME'] ==  "/ICT3103/file.php") {
     //Query for file URL
    $conn->connect();
    $qry = "SELECT * FROM file WHERE fileID = " . $_GET['fID'];
    $result = $conn->query($qry);
    
    if ($conn->num_rows($result) == 0) { 
        header("Location: 404.php");
        exit();
    }
 }
 ob_end_flush();
?>
