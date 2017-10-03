<?php

session_start();

include("db-connection.php");  // Include the class file for database access
$conn = new Mysql_Driver();  // Create an object for database access
//Update File
if (isset($_POST['actionEdit'])) { 
    
    $fileID = $_POST["actionEdit"];
    
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
      
    header("Location: fileManager.php");
}


//Delete File
if (isset($_POST['actionDelete'])) {

    $fileID = $_POST["actionDelete"];
    
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
    
    $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has delete successfully deleted from the system!";
    
    header("Location: fileManager.php");
}


//Dynamically Add/Remove Textbox
$number = count($_POST["name"]);  
 if($number > 0)  
 {  
      for($i=0; $i<$number; $i++)  
      {  
           if(trim($_POST["name"][$i] != ''))  
           {  
                $sql = "INSERT INTO tbl_name(name) VALUES('".mysqli_real_escape_string($connect, $_POST["name"][$i])."')";  
                mysqli_query($connect, $sql);  
           }  
      }  
      echo "Data Inserted";  
 }  
 else  
 {  
      echo "Please Enter Name";  
 }  





?>
