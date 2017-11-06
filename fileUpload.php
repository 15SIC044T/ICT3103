<?php
// start session
include "checkSession.php";
if (!isset($_SESSION['SESS_ACC_ID'])) {
    header("Location: index.php");
}
define('AES_256_CBC', 'aes-256-cbc'); 

if (!empty($_FILES)) {
    
    $upload_dir = "uploads/";
    $fileName = $_FILES['file']['name'];

    $okay = true;
    $allowed =  array('png','jpg','gif','bmp','jpeg','ico','mp3','txt','sql','pdf','docx','doc','xlsx','xls','csv','zip','rar','7z','xml','html','htm','php');
    if (!in_array(pathinfo($fileName, PATHINFO_EXTENSION), $allowed)) {
        $okay = false;
    }
    
    if ($_FILES["uploaded_file"]["size"] >= 5000000)
        $okay = false;
     
    
    if ($okay){   
        $ext = pathinfo($upload_dir . $fileName);
        $uploaded_file = $upload_dir .  $_SESSION['SESS_ACC_ID'] . "_" . date("Ymd-hisa") . "_" . $fileName ;

        $msg = "";
        $VTAPIKEY = "e6a99e1902664b5b89c352fb9a290cee161561c9accf9910f030a5f4e6edf58d";
        $apiEndPoint = "https://www.virustotal.com/vtapi/v2/file/report?";
        $finalURL = $apiEndPoint."resource=$hash&apikey=".$VTAPIKEY;


        $json=file_get_contents($finalURL);
        $j=json_decode($json);


        $aesKey = "keys/aes/" . substr($fileName, 0, strrpos($fileName, ".")) . "_" . date("Y-m-d_H-i-s",time()) . "_aes.key";
        $encryption_key = openssl_random_pseudo_bytes(32);
        file_put_contents($aesKey, $encryption_key);
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
        $data = file_get_contents($_FILES['file']['tmp_name']);

        $encrypted = openssl_encrypt($data, AES_256_CBC, $encryption_key, 0, $iv);
        $encrypted = $encrypted . ':' . base64_encode($iv);
        file_put_contents($_FILES['file']['tmp_name'], $encrypted);
        $hash = hash_file('sha256', $_FILES['file']['tmp_name']);
        $fileStatus = "";
    
        if($j->response_code==1) {
            $msg .= " - Success Found Hash, ";
            if($j->positives>0){
                    $msg .=  "File is Malicious with a score of $j->positives/$j->total";
                    $fileStatus = "Malicious";
            }else{
                    $msg .=  "File is Clean!";
                    $fileStatus = "Safe";
            }
        }
        
        if($j->response_code==0){
            $msg .= "File or its analysis is not available on Virustotal";

            if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaded_file)) {

                require_once('dbConnection.php');
                //insert file information into db table 

                $stmt = $conn->prepare("INSERT INTO file (accountID, fileName, fileURL, fileType, fileSize, aesKey, hash, fileStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssssss", $_SESSION['SESS_ACC_ID'], $ext['filename'], $uploaded_file, $ext['extension'], filesize($uploaded_file), $aesKey, $hash, $fileStatus);
                $stmt->execute(); 
                $stmt->close();   
                
                $_SESSION['success_msg'] = $ext['filename'] . " uploaded successfully! " . $msg;
            }   else{
                $_SESSION['error_msg'] = "There was an error uploading the file, please try again!";
            }
        }
    }
    else
        $_SESSION['error_msg'] = $fileName ." cannot be uploaded! Please check your file extension or file size must be less 5 MB!";
        
}
?>
