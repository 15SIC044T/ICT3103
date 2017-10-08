<?php

session_start();

include("db-connection.php");  // Include the class file for database access
$conn = new Mysql_Driver();  // Create an object for database access
//Update File
if (isset($_POST['actionEdit'])) { 
    
    $fileID = $_POST["actionEdit"];
    $prevURL = $_POST["prevURL"];
    
    $fName = $_POST["txtFileName"];
    $fExpiryDate = $_POST["txtExpiryDate"];
    $fPermission = $_POST["DDLFilePermission"];
     
    if ($fExpiryDate == "")
        $datetime = "";
    else
        $datetime = date("Y-m-d H:i:s", strtotime($fExpiryDate));  
    
    $conn->connect();
    $qry = "UPDATE file SET fileName='$fName', expiryDate=NULLIF('$datetime',''), filePermission='$fPermission' WHERE fileID = $fileID";
    $conn->query($qry);
    $conn->close();
    
    $_SESSION['success_msg'] = "<strong>" . $fName . "</strong> has been updated successfully!"; 

    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
    } else {
        echo "$prevURL";
    }
}


//Delete File
if (isset($_POST['actionDelete'])) {

    $fileID = $_POST["actionDelete"];
    $prevURL = $_POST["prevURL"];
    
    //Query for file URL
    $conn->connect();
    $qry = "SELECT * FROM file WHERE fileID = $fileID";
    $result = $conn->query($qry);
    $row = $conn->fetch_array($result);
    
    $fileName = $row["fileName"];
    $fileURL = $row["fileURL"];
    
    //Delete file from database 
    $qryDelete = "DELETE FROM file WHERE fileID = $fileID";
    $conn->query($qryDelete);
    $conn->close();
 
    //Delete file from directory
    if (file_exists($fileURL)) { 
        unlink($fileURL); 
    } 
    
    $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been deleted from the system successfully!";
    
    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
    } else {
        header("Location: 404.php");
    }
}


//Share File
if (isset($_POST['actionShare'])) {

    $fileID = $_POST["actionShare"];
    $prevURL = $_POST["prevURL"];
    
    //Query for file URL
    $conn->connect();
    //Delete the existing sharing emails from database 
    $qryDelete = "DELETE FROM fileSharing WHERE fileID = $fileID";
    $conn->query($qryDelete);
    $conn->close();
    
    $myarray = $_POST['text_arr'];
    foreach($myarray as $val){

    }
    
    $qrySelect = "SELECT fs.* FROM fileSharing fs INNER JOIN account a ON fs.accountID = a.accountID WHERE a.email = $email";
    $conn->query(qrySelect);
 
    //Re-insert the sharing emails
    $qryInsert = "INSERT INTO fileSharing (fileID, accountID) VALUES ($fileID, )";
    $conn->query($qryInsert);
    $conn->close();
    
    $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been added successfully!";
    
    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
    } else {
        header("Location: 404.php");
    }
}


?>
