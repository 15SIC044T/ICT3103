<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

// sanitize the POST values
$userId = $_SESSION['SESS_ACC_ID'];
$oldPassword = sha1($_POST['inputOld']);
$newPassword = sha1($_POST['inputNew']);
$confirmPassword = sha1($_POST['inputConfirm']);

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on accountid
$queryUser = "SELECT * 
            FROM account 
            WHERE accountID = '$userId'";
$resultUser = $connection->query($queryUser);

if ($connection->num_rows($resultUser) == 1) {
    $user = $connection->fetch_array($resultUser);

    // check password with database and is available
    if ($user['password'] == $oldPassword) {
        if (empty($_POST['inputNew'])) {
            header("Location: ../profile.php");
            $_SESSION['error_msg'] = "New password not entered!";
        } else {
            // check password matched
            if ($newPassword == $confirmPassword) {
                $queryUpdate = "UPDATE account 
                                SET password = '$confirmPassword' 
                                WHERE accountID = $userId AND password = '$oldPassword'";
                $updateDB = $connection->query($queryUpdate);

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Password changed!";
            } else { // mismatched
                header("Location: ../profile.php");
                $_SESSION['error_msg'] = "Password not the same!";
            }
        }
    } else {
        header("Location: ../profile.php");
        $_SESSION['neutral_msg'] = "Invalid password!";
    }
} else {
    header("Location: ../profile.php");
    $_SESSION['error_msg'] = "Invalid password!";
}
?>