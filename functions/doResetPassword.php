<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

// sanitize the POST values
$userId = $_SESSION['SESS_ACC_ID'];
$password = $_POST['inputPass'];
$confirmPassword = $_POST['inputConfirmPass'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// check password not same
if ($password != $confirmPassword) {
    header("Location: ../confirmPasswordReset.php?id=$getUserId");
    $_SESSION['error_msg'] = "Password not the same!";
} else {
    // password hashing
    $confirmPassHash = password_hash($confirmPassword, PASSWORD_BCRYPT);

    $queryUpdate = "UPDATE account 
                    SET password = '$confirmPassHash' 
                    WHERE accountID = $userId";
    $updateDB = $connection->query($queryUpdate);

    header("Location: ../index.php");
    $_SESSION['success_msg'] = "Password changed!";
}
?>