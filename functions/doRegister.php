<?php

// start session
session_start();

// include database connection details
include '../db-connection.php';

// sanitize the POST values
$name = $_POST['inputName'];
$password = $_POST['inputPass'];
$confirmPassword = $_POST['inputConfirmPass'];
$email = $_POST['inputEmail'];
$mobile = $_POST['inputMobile'];

// connect database
$connection = new Mysql_Driver();
$connection->connect();

// look through database based on name
$queryName = "SELECT * 
            FROM account 
            WHERE name='$name'";
$resultName = $connection->query($queryName);

// look through database based on email
$queryEmail = "SELECT * 
                FROM account 
                WHERE email='$email'";
$resultEmail = $connection->query($queryEmail);

// check for name duplication
if ($connection->num_rows($resultName) == 1) {
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Name taken!";
} elseif ($connection->num_rows($resultEmail) == 1) { // check for email duplication
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Email address used!";
} elseif ($password != $confirmPassword) { // check if password same
    header("Location: ../registerAcc.php");
    $_SESSION['error_msg'] = "Password not the same!";
} else {
    // password hashing
    $confirmPassHash = password_hash($confirmPassword, PASSWORD_BCRYPT);

    $queryAdd = "INSERT INTO account(name, email, password, phone, accountStatus) 
                VALUES('$name','$email','$confirmPassHash','$mobile','Unverified')";
    $addUser = $connection->query($queryAdd);

    header("Location: ../index.php");
    $_SESSION['success_msg'] = "Register Done! Please check email to verify your account!";
}
?>