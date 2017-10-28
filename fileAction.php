<?php
define('AES_256_CBC', 'aes-256-cbc');
session_start();

include("db-connection.php");  // Include the class file for database access
$conn = new Mysql_Driver();  // Create an object for database access
//Update File
if (isset($_POST['actionEdit'])) { 
    
    $errorForm = false;
    
    $fileID = $_POST["actionEdit"];
    $prevURL = $_POST["prevURL"]; 
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    
    $fName = filter_var($_POST["txtFileName"], FILTER_SANITIZE_STRING);
    $fExpiryDate = $_POST["txtExpiryDate"];
    $fPermission = $_POST["DDLFilePermission"];
      
    //sanitize input
    if (!(filter_var($fileID, FILTER_VALIDATE_INT) === 0 || !filter_var($fileID, FILTER_VALIDATE_INT) === false)) { 
        $errorForm = true;
    }
    
    if (!(!filter_var($prevURL, FILTER_VALIDATE_URL) === false)) {
        $errorForm = true;
    }
    
    
    //If expiryDate set 
    if ($fExpiryDate < date("Y-m-d H:i:s"))
    {  
        $conn->connect();
        if ($fPermission == "Public") {
            $qry = "SELECT fileURL, aesKey FROM file WHERE fileID = $fileID";
            
            $result = $conn->query($qry);
  
            if ($conn->num_rows($result) > 0) {
                while ($row = $conn->fetch_array($result)) {
                    $file = $row["fileURL"];
                    $aKey = $row["aesKey"];
                }
            }
            
            $fileData = file_get_contents($file);
            $aes = file_get_contents($aKey);
            $parts = explode(':', $fileData);
            $decrypted = openssl_decrypt($parts[0], AES_256_CBC, $aes, 0, base64_decode($parts[1]));
            $pFile = "uploads/public/" . substr($file, 8);
            file_put_contents($pFile, $decrypted);
            
            $qry2 = "UPDATE file SET publicURL='$pFile' WHERE fileID = $fileID";
            $conn->query($qry2);
            $conn->close();
        }
        
        if ($fExpiryDate == "")
            $datetime = "";
        else
            $datetime = date("Y-m-d H:i:s", strtotime($fExpiryDate));  

        $conn->connect();
        $qry = "UPDATE file SET fileName='$fName', expiryDate=NULLIF('$datetime',''), filePermission='$fPermission' WHERE fileID = $fileID";
        $conn->query($qry);
        $conn->close();

        $_SESSION['success_msg'] = "<strong>" . $fName . "</strong> has been updated successfully! ";  
        
        $fPermission = "private";
    
        $exist = false;
        $conn->connect(); 
        //check if email exists, if exist add, else error
        $qry = "SELECT accountID FROM account WHERE email = '" . $email . "'";
        $result = $conn->query($qry);

        if ($conn->num_rows($result) > 0) {
            while ($row = $conn->fetch_array($result)) {
                $accountID = $row["accountID"];
                $exist = true;
            }
        } 

        
        if ($exist) {
            
            $existShared = false;
            //check if the file already inserted into the table
            //check if email exists, if exist add, else error
            $qry3 = "SELECT accountID FROM filesharing WHERE fileID = $fileID AND accountID IN (SELECT accountID FROM account WHERE email = '$email') "
                    . "UNION "
                    . "SELECT accountID FROM file WHERE fileID = $fileID AND accountID IN (SELECT accountID FROM account WHERE email = '$email')";
            $result3 = $conn->query($qry3);

            if ($conn->num_rows($result3) > 0) {
                while ($row3 = $conn->fetch_array($result3)) {
                    $accountID = $row3["accountID"];
                    $existShared = true;
                }
            } 
            
            if (!$existShared) {
                $qry2 = "SELECT f.aesKey, a.publicKey FROM file f, account a WHERE f.fileID = ".$fileID." AND a.accountID = ". $accountID;
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
                $qryInsert = "INSERT INTO filesharing (fileID, accountID, invitationAccepted, eAesKey) VALUES ($fileID, $accountID, 1, '$eAes')";
                $conn->query($qryInsert);
                $conn->close();

                //After insert, update the status of filePermission to "private" just in case someone add sharing users to public 
                $conn->connect();
                $qry = "UPDATE file SET filePermission='$fPermission' WHERE fileID = $fileID";
                $conn->query($qry);
                $conn->close();
 
                $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been shared and save successfully!"; 
            }
            else {
                $_SESSION['error_msg'] = "The email account has already been shared!";
            }
        } else {
            if (!($email == "" || $email == null)) 
                $_SESSION['error_msg'] = "The email account does not exist! Cannot share file!";
        }

        if (strpos($prevURL, "file.php")) {
            header("Location: ". $prevURL);
            exit();
        } elseif (strpos($prevURL, "fileManager.php")) {
            header("Location: fileManager.php");
            exit();
        } else {
            echo "$prevURL";
        }
    }
}


//Delete File
if (isset($_POST['actionDelete'])) {

    $fileID = $_POST["actionDelete"];
    $prevURL = $_POST["prevURL"];
    
    $errorForm = false;
    //sanitize input
    if (!(filter_var($fileID, FILTER_VALIDATE_INT) === 0 || !filter_var($fileID, FILTER_VALIDATE_INT) === false)) { 
        $errorForm = true;
    }
    
    if (!(!filter_var($prevURL, FILTER_VALIDATE_URL) === false)) {
        $errorForm = true;
    }
    
    //Query for file URL
    $conn->connect();
    $qry = "SELECT * FROM file WHERE fileID = $fileID AND accountID = " . $_SESSION['SESS_ACC_ID'];
    $result = $conn->query($qry);
    $row = $conn->fetch_array($result);
    
    $fileName = $row["fileName"];
    $fileURL = $row["fileURL"];
    
    //Delete file from database 
    $qryDelete = "DELETE FROM file WHERE fileID = $fileID AND accountID = " . $_SESSION['SESS_ACC_ID'];
    $conn->query($qryDelete);
    $conn->close();
 
    //Delete file from directory
    if (file_exists($fileURL)) { 
        unlink($fileURL); 
    } 
    
    $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been deleted from the system successfully!";
    
    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
        exit();
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
        exit();
    } else {
        header("Location: 404.php");
        exit();
    }
}

//Share File
if (isset($_POST['actionDeIete'])) { 
    
    $fileID = $_POST["actionDeIete"]; 
    $prevURL = $_POST["prevURL"];
    
     //Query for file URL
    $conn->connect();
    //Delete the existing sharing emails from database 
    $qryDelete = "DELETE FROM filesharing WHERE fileID = $fileID AND accountID = " . $_SESSION["SESS_ACC_ID"];
    $conn->query($qryDelete);
    $conn->close();  
    
    //Query for file URL
    $conn->connect();
    $qry = "SELECT * FROM file WHERE fileID = $fileID AND accountID = " . $_SESSION['SESS_ACC_ID'];
    $result = $conn->query($qry);
    $row = $conn->fetch_array($result);
    
    $fileName = $row["fileName"];
    
    $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been unlinked successfully!";
    $conn->close();  
     
    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
        exit();
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
        exit();
    } else {
        header("Location: 404.php");
        exit();
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
    $qryDelete = "DELETE FROM filesharing WHERE filesharingID = $sharedID";
    $conn->query($qryDelete);
    $conn->close();  
    
     $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been unlinked successfully!";
    
    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
        exit();
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
        exit();
    } else {
        header("Location: 404.php");
        exit();
    }
}


//Share File - Insert record to data table ONE-BY-ONE
if (isset($_POST['actionShare'])) {

    $fileID = $_POST["actionShare"]; 
    $prevURL = $_POST["prevURL"];
    $email = filter_var($_POST["txtEmail"], FILTER_SANITIZE_EMAIL);
    
    
    if (strpos($prevURL, "file.php")) {
        header("Location: ". $prevURL);
        exit();
    } elseif (strpos($prevURL, "fileManager.php")) {
        header("Location: fileManager.php");
        exit();
    } else {
        header("Location: 404.php");
        exit();
    }
}
?>
