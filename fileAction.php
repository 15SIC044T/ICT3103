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
     
    //If expiryDate set 
    if ($fExpiryDate < now())
    {
    
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
if (isset($_POST['actionDelShare'])) { 
    
    $sharedEmail = $_POST["sharedEmaail"];
    $sharedID = $_POST["actionDelShare"]; 
    $prevURL = $_POST["prevURL"];
    
     //Query for file URL
    $conn->connect();
    //Delete the existing sharing emails from database 
    $qryDelete = "DELETE FROM fileSharing WHERE fileSharingID = $sharedID";
    $conn->query($qryDelete);
    $conn->close();  
    
     $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been unlinked successfully!";
    
    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
    } else {
        header("Location: 404.php");
    }
}


//Share File - Insert record to data table ONE-BY-ONE
if (isset($_POST['actionShare'])) {

    $fileID = $_POST["actionShare"]; 
    $prevURL = $_POST["prevURL"];
    $email = $_POST["txtEmail"];
    
    $conn->connect();
    
    //check if email exists, if exist add, else error
    $qry = "SELECT accountID FROM account WHERE email = '" . $email . "'";
    $result = $conn->query($qry);
  
    if ($conn->num_rows($result) > 0) {
        while ($row = $conn->fetch_array($result)) {
            $accountID = $row["accountID"];
        }
    } 
 
    //Insert the sharing emails
    $qryInsert = "INSERT INTO fileSharing (fileID, accountID, invitationAccepted) VALUES ($fileID, $accountID, 1)";
    $conn->query($qryInsert);
    $conn->close();
    
    $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been shared successfully!";
    
    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
    } else {
        header("Location: 404.php");
    }
}


?>
