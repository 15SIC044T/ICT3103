<?php
define('AES_256_CBC', 'aes-256-cbc'); 

include "checkSession.php";
if (!isset($_SESSION['SESS_ACC_ID'])) {
    header("Location: index.php");
} 

//Update File
if (isset($_POST['actionEdit'])) { 
    
    $errorForm = false; 
    $fileHashing = $_POST["actionEdit"];
    $fileID = 0;
    foreach ($_SESSION['fileArray'] as $product) {
        if ($product['hashID'] == $fileHashing) {
            $fileID = $product['fileID'];
            break;
        }
    }
    
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
        if ($fPermission == "Public") { 
            
            $stmt = $conn->prepare("SELECT fileURL, aesKey FROM file WHERE fileID = ? AND accountID = ?");
            $stmt->bind_param("ii", $fileID, $_SESSION['SESS_ACC_ID']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
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
             
            $stmt = $conn->prepare("UPDATE file SET publicURL=? WHERE fileID = ? AND accountID = ?"); 
            $stmt->bind_param('sii', $pFile, $fileID, $_SESSION['SESS_ACC_ID']);
            $stmt->execute();  
            $stmt->close();
        }
        
        if ($fExpiryDate == "")
            $datetime = "";
        else
            $datetime = date("Y-m-d H:i:s", strtotime($fExpiryDate));  

        $stmt = $conn->prepare("UPDATE file SET fileName=?, expiryDate=NULLIF(?,''), filePermission=? WHERE fileID = ? AND accountID = ?"); 
        $stmt->bind_param('sssii', $fName, $datetime, $fPermission, $fileID, $_SESSION['SESS_ACC_ID']);
        $stmt->execute();  
        $stmt->close(); 

        $_SESSION['success_msg'] = "<strong>" . $fName . "</strong> DETAILS has been updated successfully! ";  
        
        if ($fPermission == "Private") { 
            //$fPermission = "private"; 
            $exist = false;

            $stmt = $conn->prepare("SELECT accountID FROM account WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $accountID = $row["accountID"];
                    $exist = true;
                }
            }   
            $stmt->close(); 

            if ($exist) { 
                $existShared = false;
                //check if the file already inserted into the table
                //check if email exists, if exist add, else error  
                $stmt = $conn->prepare("SELECT accountID FROM filesharing WHERE fileID = ? AND accountID IN (SELECT accountID FROM account WHERE email = ?) "
                        . "UNION SELECT accountID FROM file WHERE fileID = ? AND accountID IN (SELECT accountID FROM account WHERE email = ?)");
                $stmt->bind_param("isis", $fileID, $email, $fileID, $email);
                $stmt->execute();
                $result3 = $stmt->get_result();

                if ($result3->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
                        $accountID = $row3["accountID"];
                        $existShared = true;
                    }
                } 
                $stmt->close(); 

                if (!$existShared) {
                    $stmt = $conn->prepare("SELECT f.aesKey, a.publicKey, f.fileName FROM file f, account a WHERE f.fileID = ? AND a.accountID = ?");
                    $stmt->bind_param("ii", $fileID, $accountID);
                    $stmt->execute();
                    $result2 = $stmt->get_result(); 

                    if ($result2->num_rows > 0) {
                        while ($row = $result2->fetch_assoc()) {
                            $fileName = $row["fileName"];
                            $aKey = $row["aesKey"];
                            $pKey = $row["publicKey"];
                        }
                    } 
                    $stmt->close(); 

                    $pKey = substr($pKey, 3);
                    $eAes = "keys/eAes/" . $fileID . "_" . $accountID . "_" . date("Y-m-d_H-i-s",time()) . "_eAes.key";
                    $data = file_get_contents($aKey);
                    $publicKey = file_get_contents($pKey);
                    openssl_public_encrypt($data, $encrypted, $publicKey);
                    file_put_contents($eAes, $encrypted);

                    //Insert the sharing emails 
                    $stmt = $conn->prepare("INSERT INTO filesharing (fileID, accountID, invitationAccepted, eAesKey) VALUES (?, ?, 1, ?)");
                    $stmt->bind_param("iis", $fileID, $accountID, $eAes);
                    $stmt->execute();
                    $stmt->close(); 

                    //After insert, update the status of filePermission to "private" just in case someone add sharing users to public
                    $stmt = $conn->prepare("UPDATE file SET filePermission=? WHERE fileID = ?");
                    $stmt->bind_param("si", $fPermission, $fileID);
                    $stmt->execute();
                    $stmt->close();  

                    $_SESSION['success_msg'] = "<strong>" . $fileName . "</strong> has been shared and save successfully!"; 
                }
                else {
                    $_SESSION['error_msg'] = "The email account has already been shared or you cannot share to yourself!";
                }
            } else {
                if (!($email == "" || $email == null)) 
                    $_SESSION['error_msg'] = "The email account does not exist! Cannot share file!";
            }
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
    } else {
        $_SESSION['error_msg'] = "You cannot set past expiry date!";
    }
        
}


//Delete File
if (isset($_POST['actionDelete'])) {
 
    $fileHashing = $_POST["actionDelete"];
    $fileID = 0;
    foreach ($_SESSION['fileArray'] as $product) {
        if ($product['hashID'] == $fileHashing) {
            $fileID = $product['fileID'];
            break;
        }
    }
    $prevURL = $_POST["prevURL"];
    $accID = $_SESSION["SESS_ACC_ID"];
    
    $errorForm = false;
    //sanitize input
    if (!(filter_var($fileID, FILTER_VALIDATE_INT) === 0 || !filter_var($fileID, FILTER_VALIDATE_INT) === false)) { 
        $errorForm = true;
    }
    
    if (!(!filter_var($prevURL, FILTER_VALIDATE_URL) === false)) {
        $errorForm = true;
    }
    
    //Query for file URL 
    $stmt = $conn->prepare("SELECT fileName, fileURL, aesKey FROM file WHERE fileID = ? AND accountID = ?");
    $stmt->bind_param("ii", $fileID, $accID);
    $stmt->execute();
    $result = $stmt->get_result();  
    $row = $result->fetch_assoc(); 
    
    $aesKey = $row["aesKey"];
    $fileName = $row["fileName"];
    $fileURL = $row["fileURL"];
    $stmt->close();  
    
    //Delete file from database 
    $stmt = $conn->prepare("DELETE FROM file WHERE fileID = ? AND accountID = ?");
    $stmt->bind_param("ii", $fileID, $accID);
    $stmt->execute();
    $stmt->close(); 
    
    //delete the existing key as well for file sharing
    $stmt = $conn->prepare("SELECT eAesKey FROM filesharing WHERE fileID = ?");
    $stmt->bind_param("i", $fileID);
    $stmt->execute();
    $result = $stmt->get_result();  
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            //Delete the existing key as well from file directory as well
            if (file_exists($row['eAesKey'])) {
                unlink($row['eAesKey']);
            }
        }
    } 
    
    //Delete the existing key as well from file directory as well
    if (file_exists($aesKey)) { 
        unlink($aesKey); 
    } 
    
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
     
    $fileHashing = $_POST["actionDeIete"];
    $fileID = 0;
    foreach ($_SESSION['fileArray'] as $product) {
        if ($product['hashID'] == $fileHashing) {
            $fileID = $product['fileID'];
            break;
        }
    }
    $prevURL = $_POST["prevURL"];
    $accID = $_SESSION["SESS_ACC_ID"];
     
    //Query for file URL 
    $stmt = $conn->prepare("SELECT eAesKey FROM filesharing WHERE fileID = ? AND accountID = ?");
    $stmt->bind_param("ii", $fileID, $accID);
    $stmt->execute();
    $result = $stmt->get_result();  
    $row = $result->fetch_assoc(); 
    if (file_exists($row['eAesKey'])) {
        unlink($row['eAesKey']);
    }
    $stmt->close(); 
    
    //Delete the existing sharing emails from database   
    $stmt = $conn->prepare("DELETE FROM filesharing WHERE fileID = ? AND accountID = ?");
    $stmt->bind_param("ii", $fileID, $accID);
    $stmt->execute();
    $stmt->close(); 
    
    //Query for file URL 
    $stmt = $conn->prepare("SELECT fileName FROM file WHERE fileID = ? AND accountID = ?");
    $stmt->bind_param("ii", $fileID, $accID);
    $stmt->execute();
    $result = $stmt->get_result();  
    $row = $result->fetch_assoc(); 
    $fileName = $row["fileName"];
    $stmt->close(); 
    
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


//Share File
if (isset($_POST['actionDelShare'])) { 
     
    $hashSharedFileID = $_POST["actionDelShare"];
    $sharedID = 0;
    foreach ($_SESSION['fileSharedArray'] as $product) {
        if ($product['countID'] == $hashSharedFileID) {
            $sharedID = $product['fileSharedID'];
            break;
        }
    } 
    
    $sharedEmail = $_POST["sharedEmail"]; 
    $prevURL = $_POST["prevURL"];
    $accID = $_SESSION["SESS_ACC_ID"];
    
    //Query for file URL 
    $stmt = $conn->prepare("SELECT eAesKey FROM filesharing WHERE filesharingID = ?");
    $stmt->bind_param("i", $sharedID);
    $stmt->execute();
    $result = $stmt->get_result();  
    $row = $result->fetch_assoc(); 
    if (file_exists($row['eAesKey'])) {
        unlink($row['eAesKey']);
    }
    $stmt->close();  
    
     //Query for file URL 
    //Delete the existing sharing emails from database  
    $stmt = $conn->prepare("DELETE FROM filesharing WHERE filesharingID = ?");
    $stmt->bind_param("i", $sharedID);
    $stmt->execute();
    $stmt->close();  
    
    //Query for file URL 
    $stmt = $conn->prepare("SELECT fileName FROM file WHERE fileID = ? AND accountID = ?");
    $stmt->bind_param("ii", $fileID, $accID);
    $stmt->execute();
    $result = $stmt->get_result();  
    $row = $result->fetch_assoc(); 
    $fileName = $row["fileName"];
    $stmt->close(); 

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
 
    $fileHashing = $_POST["actionShare"];
    $fileID = 0;
    foreach ($_SESSION['fileArray'] as $product) {
        if ($product['hashID'] == $fileHashing) {
            $fileID = $product['fileID'];
            break;
        }
    }
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
