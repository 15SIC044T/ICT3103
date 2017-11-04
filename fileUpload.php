<?php
// start session
session_start();
define('AES_256_CBC', 'aes-256-cbc'); 

if (!empty($_FILES)) {
    
    $upload_dir = "uploads/";
    $fileName = $_FILES['file']['name'];
    $ext = pathinfo($upload_dir . $fileName);
    $uploaded_file = $upload_dir .  $_SESSION['SESS_ACC_ID'] . "_" . date("Ymd-hisa") . "_" . $fileName ;
    
    $aesKey = "keys/aes/" . substr($fileName, 0, strrpos($fileName, ".")) . "_" . date("Y-m-d_H-i-s",time()) . "_aes.key";
    $encryption_key = openssl_random_pseudo_bytes(32);
    file_put_contents($aesKey, $encryption_key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
    $data = file_get_contents($_FILES['file']['tmp_name']);
    
    $encrypted = openssl_encrypt($data, AES_256_CBC, $encryption_key, 0, $iv);
    $encrypted = $encrypted . ':' . base64_encode($iv);
    file_put_contents($_FILES['file']['tmp_name'], $encrypted);
    $hash = hash_file('sha256', $_FILES['file']['tmp_name']);
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaded_file)) {
 
        require_once('dbConnection.php');
        //insert file information into db table 
        
        $stmt = $conn->prepare("INSERT INTO file (accountID, fileName, fileURL, fileType, fileSize, aesKey, hash) VALUES (?, ?, ?, ?, ?, ?, ?)");
	$stmt->bind_param("issssss", $_SESSION['SESS_ACC_ID'], $ext['filename'], $uploaded_file, $ext['extension'], filesize($uploaded_file), $aesKey, $hash);
        $stmt->execute();
        $stmt->close();   
    }
}
?>
