<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

// sanitize the POST values
$userId = $_SESSION['SESS_ACC_ID'];
$password = sha1($_POST['inputPass']);
$confirmPassword = sha1($_POST['inputConfirmPass']);

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// check password not same
if ($password != $confirmPassword) {
    header("Location: ../confirmPasswordReset.php?id=$getUserId");
    $_SESSION['error_msg'] = "Password not the same!";
    exit();
} else {
    $queryUpdate = "UPDATE account 
                    SET password = '$confirmPassword' 
                    WHERE accountID = $userId";
    $updateDB = $connection->query($queryUpdate);

    header("Location: ../index.php");
    $_SESSION['success_msg'] = "Password Changed!";
    exit();
}
?>