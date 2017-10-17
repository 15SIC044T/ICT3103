<?php

// start session
session_start();

// include database connection details
include "../db-connection.php";

// sanitize the POST values
$resetPassToken = $_SESSION['SESS_TOKEN'];
$password = $_POST['inputPass'];
$confirmPassword = $_POST['inputConfirmPass'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// check password not same
if ($password != $confirmPassword) {
    header("Location: ../confirmPasswordReset.php?t=$resetPassToken");
    $_SESSION['error_msg'] = "Password not the same!";
} else {
    // password hashing
    $confirmPassHash = password_hash($confirmPassword, PASSWORD_BCRYPT);
    
    $nullValue = 'NULL';

    $queryUpdate = "UPDATE account 
                    SET password = '$confirmPassHash', 
                        resetPasswordToken = $nullValue 
                    WHERE resetPasswordToken = '$resetPassToken'";
    $updateDB = $connection->query($queryUpdate);

    header("Location: ../index.php");
    $_SESSION['success_msg'] = "Password changed!";
}
?>