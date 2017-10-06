<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

// sanitize the POST values
$getUserId = $_SESSION['SESS_ACC_ID'];
$getName = $_POST['inputName'];
$getEmail = $_POST['inputEmail'];
$getMobile = $_POST['inputMobile'];

echo $getUserId;
echo $getName;
echo $getEmail;
echo $getMobile;

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on accountid
$queryUser = "SELECT * 
            FROM account 
            WHERE accountID = '$getUserId'";
$resultUser = $connection->query($queryUser);

// look through database based on name
$queryName = "SELECT * 
            FROM account 
            WHERE name = '$getName'";
$resultName = $connection->query($queryName);

// look through database based on email
$queryEmail = "SELECT * 
            FROM account 
            WHERE email = '$getEmail'";
$resultEmail = $connection->query($queryEmail);

if ($connection->num_rows($resultUser) == 1) {
    $user = $connection->fetch_array($resultUser);

    echo $user['name'];

    // check mobile not changed
    if ($user['phone'] == $getMobile) {
        // check other info not changed
        if ($user['name'] == $getName && $user['email'] == $getEmail) {
            header("Location: ../profile.php");
            $_SESSION['neutral_msg'] = "No changes made!";
        } else { // other info changed
            // check name duplication
            if ($connection->num_rows($resultName) == 0 || $connection->num_rows($resultEmail) == 0) {
                $queryUpdate = "UPDATE account 
                            SET name = '$getName', email = '$getEmail', phone = '$getMobile' 
                            WHERE accountID = $getUserId";
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
                        SET name = '$getName', email = '$getEmail', phone = '$getMobile' 
                        WHERE accountID = $getUserId";
        $updateDB = $connection->query($queryUpdate);

        header("Location: ../profile.php");
        $_SESSION['success_msg'] = "Changes made!";
    }
}
?>