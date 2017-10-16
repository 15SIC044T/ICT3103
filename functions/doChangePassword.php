<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

// sanitize the POST values
$userId = $_SESSION['SESS_ACC_ID'];
$oldPassword = $_POST['inputOld'];
$newPassword = $_POST['inputNew'];
$confirmPassword = $_POST['inputConfirm'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on accountID
$queryUser = "SELECT * 
            FROM account 
            WHERE accountID = '$userId'";
$resultUser = $connection->query($queryUser);

if ($connection->num_rows($resultUser) == 1) {
    $user = $connection->fetch_array($resultUser);
    $dbPassHash = $user['password'];

    // check old password with database
    $verifyPassword = password_verify($oldPassword, $dbPassHash);

    // if old password valid in database
    if ($verifyPassword == 1) {
        if (empty($newPassword)) {
            header("Location: ../profile.php");
            $_SESSION['error_msg'] = "New password not entered!";
        } elseif (empty($confirmPassword)) {
            header("Location: ../profile.php");
            $_SESSION['error_msg'] = "Please confirm your new password!";
        } else {
            if ($newPassword == $confirmPassword) {
                // password hashing
                $confirmPassHash = password_hash($confirmPassword, PASSWORD_BCRYPT);

                $queryUpdate = "UPDATE account 
                                SET password = '$confirmPassHash' 
                                WHERE accountID = $userId";
                $updateDB = $connection->query($queryUpdate);

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Password changed!";
            } else {
                header("Location: ../profile.php");
                $_SESSION['error_msg'] = "Password not the same! Please confirm again!";
            }
        }
    } else { // password not found in database
        if (empty($oldPassword) && !empty($newPassword)) {
            header("Location: ../profile.php");
            $_SESSION['error_msg'] = "Please enter old password!";
        } elseif (empty($oldPassword)) {
            header("Location: ../profile.php");
            $_SESSION['neutral_msg'] = "No change made to your password!";
        } else { // password not found in database
            header("Location: ../profile.php");
            $_SESSION['neutral_msg'] = "Password not found in database!";
        }
    }
} else {
    header("Location: ../profile.php");
    $_SESSION['error_msg'] = "Invalid password!";
}
?>