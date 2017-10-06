<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

// sanitize the POST values
$userId = $_SESSION['SESS_ACC_ID'];
$name = $_POST['inputName'];
$email = $_POST['inputEmail'];
$mobile = $_POST['inputMobile'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on accountid
$queryUser = "SELECT * 
            FROM account 
            WHERE accountID = '$userId'";
$resultUser = $connection->query($queryUser);

// look through database based on name
$queryName = "SELECT * 
            FROM account 
            WHERE name = '$name'";
$resultName = $connection->query($queryName);

// look through database based on email
$queryEmail = "SELECT * 
            FROM account 
            WHERE email = '$email'";
$resultEmail = $connection->query($queryEmail);

if ($connection->num_rows($resultUser) == 1) {
    $user = $connection->fetch_array($resultUser);

    echo $user['name'];

    // check mobile not changed
    if ($user['phone'] == $mobile) {
        // check other info not changed
        if ($user['name'] == $name && $user['email'] == $email) {
            header("Location: ../profile.php");
            $_SESSION['neutral_msg'] = "No changes made!";
        } else { // other info changed
            // check name duplication
            if ($connection->num_rows($resultName) == 0 || $connection->num_rows($resultEmail) == 0) {
                $queryUpdate = "UPDATE account 
                            SET name = '$name', email = '$email', phone = '$mobile' 
                            WHERE accountID = $userId";
                $updateDB = $connection->query($queryUpdate);

                header("Location: ../profile.php");
                $_SESSION['success_msg'] = "Changes made!";
            } elseif ($connection->num_rows($resultName) == 1) {
                header("Location: ../profile.php");
                $_SESSION['error_msg'] = "Name taken!";
            } elseif ($connection->num_rows($resultEmail) == 1) { // check email duplication
                header("Location: ../profile.php");
                $_SESSION['error_msg'] = "Email address taken!";
            }
        }
    } else { // check mobile changed
        $queryUpdate = "UPDATE account 
                        SET name = '$name', email = '$email', phone = '$mobile' 
                        WHERE accountID = $userId";
        $updateDB = $connection->query($queryUpdate);

        header("Location: ../profile.php");
        $_SESSION['success_msg'] = "Changes made!";
    }
}
?>